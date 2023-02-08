class WRoot extends ContainerWidget {
  #json;
  get json () {
    return this.#json;
  }
  set json (json) {
    this.#json = json;
    this.dispatchJSONEvent();
  }
  constructor (json, parent, editable = false) {
    super(
      Div("w-root"),
      parent,
      editable
    );
    this.removeMargin();
    this.editable = editable;
    this.#json = json;
    this.#json.webpage = Object.assign({}, webpage);
    this.header = WHeader.build({
      titleAlign: json.headerTitleAlign,
      titleColor: json.headerTitleColor,
    }, this, editable);
    this.page = WPage.build({}, this, editable);
    this.commentSection = WCommentSection.build({
      areCommentsAvailable: json.areCommentsAvailable
    }, this, editable);
    this.appendWidget(this.header);
    this.appendWidget(this.page);
    this.appendWidget(this.commentSection);
  }
  #listeners = [];
  addJSONListener (callback) {
    this.#listeners.push(callback);
  }
  dispatchJSONEvent () {
    for (const listener of this.#listeners) {
      listener(this.#json);
    }
  }
  draggingWidget;
  static async #createRoot (json, editable = false) {
    if (!editable) {
      await widgets.request(...await this.walkWStructure(json, new Set()))
    }
    const root = new WRoot(json, null, editable);
    for (const child of json.children) {
      await root.page.appendWidget(widgets.get(child.type).build(child, root.page, editable));
    }
    return root;
  }
  static async walkWStructure (widget, importSet, bypassExistsRestriction = false) {
    if (!widgets.exists(widget.type) || bypassExistsRestriction) {
      importSet.add(widget.type);
    }
    if (widget.children) {
      for (const child of widget.children) {
        importSet = await this.walkWStructure(child, importSet);
      }
    }
    return importSet;
  }
  static async build (json, parent = null, editable = false) {
    return await this.#createRoot(json, editable);
  }
  static default (parent, editable = false) {
    return new WRoot({}, parent);
  }
  get inspectorHTML () {
    const headerTitleColorPicker = new ColorPicker(true);
    const pickerID = guid(true);
    headerTitleColorPicker.rootElement.id = pickerID;
    untilElement("#" + pickerID)
      .then(() => {
        const formatLabel = headerTitleColorPicker.rootElement.querySelector(".format-label");
        const revertButton = Button("button-like-main", "Revert", () => {
          headerTitleColorPicker.setNewFromFormat("#000");
          this.#json.headerTitleColor = undefined;
          this.dispatchJSONEvent();
        });
        formatLabel.parentElement.insertBefore(revertButton, formatLabel);
        formatLabel.remove();
      })
    headerTitleColorPicker.setNewFromFormat(this.#json.headerTitleColor ?? "#000000ff");
    headerTitleColorPicker.rootElement.addEventListener("pick", evt => {
      this.#json.headerTitleColor = ColorPicker.toHEX(evt.detail);
      this.dispatchJSONEvent();
    })
    const headerSettings = [
      HRInspector(this.#json.isHeaderIncluded ? "" : "display-none"),
      TitleInspector("Header", this.#json.isHeaderIncluded ? "" : "display-none"),
      Div("i-header-settings inner-padding" + (this.#json.isHeaderIncluded ? "" : " display-none"), [
        Div("i-row", [
          Span(__, "Image:"),
          Div("i-row", [
            Button("button-like-main", "Remove", async (evt) => {
              if (this.#json.webpage.thumbnail === undefined) return;
              const response = await AJAX.patch("/page/", JSONHandler(), {
                body: JSON.stringify({
                  id: webpage.ID,
                  property: "thumbnailSRC",
                  value: null
                })
              });
              if (response.error !== undefined) {
                rejected(evt.target.parentElement.parentElement);
                alert(response.error);
                return;
              }
              validated(evt.target.parentElement.parentElement);
              this.#json.webpage.thumbnail = undefined;
              this.dispatchJSONEvent();
            }),
            Button("button-like-main", "Select", (evt) => {
              const win = showWindow("file-select");
              win.dataset.multiple = "false";
              win.dataset.fileType = "image";
              win.dispatchEvent(new Event("fetch"));
              win.onsubmit = async submitEvent => {
                const response = await AJAX.patch("/page/", JSONHandler(), {
                  body: JSON.stringify({
                    id: webpage.ID,
                    property: "thumbnailSRC",
                    value: submitEvent.detail[0].src
                  })
                });
                if (response.error !== undefined) {
                  alert(response.error);
                  rejected(evt.target.parentElement.parentElement);
                  return;
                }
                validated(evt.target.parentElement.parentElement);
                this.#json.webpage.thumbnail = submitEvent.detail[0].serverName;
                this.dispatchJSONEvent();
              }
            })
          ])
        ]),
        RadioGroupInspector(value => {
          this.#json.headerTitleAlign = value;
          this.dispatchJSONEvent();
          return true;
        }, selectOption([
          { text: "Left", value: "start" },
          { text: "Center", value: "center" },
          { text: "Right", value: "end" },
        ], this.#json.headerTitleAlign, "center"), "Title align:"),
        Div(__, "Title color:"),
        headerTitleColorPicker.rootElement,
      ])
    ];
    const releaseDate = DateInspector(
      webpage.releaseDate !== undefined
        ? new Date(webpage.releaseDate)
        : undefined,
      async (value, parentElement) => {
        const response = await AJAX.patch("/page/release-date", JSONHandler(), {
          body: JSON.stringify({
            id: webpage.ID,
            releaseDate: value.toISOString()
          })
        });
        if (response.error !== undefined || response.rowCount !== 1) {
          rejected(parentElement);
          console.log(response);
          return false;
        }
        this.#json.webpage.releaseDate = value.toISOString();
        this.dispatchJSONEvent();
        validated(parentElement);
        return true;
      },
      "Release date:",
      true
    );
    const releaseDateInput = releaseDate.querySelector("input");
    if (this.#json.webpage.releaseDate === undefined) {
      releaseDate.classList.add("display-none");
    }
    const themeContent = Div("content");
    const themeLabel = Span(__, "Loading theme...", );
    const themeSelect = Div("select-dropdown", [
      Div("label", [
        themeLabel,
        SVG("icon-arrow", "icon")
      ]),
      themeContent
    ], {
      attributes: {style: "--height: 200px"},
      listeners: {click: evt => {
        themeSelect.classList.toggle("expand");
        evt.stopImmediatePropagation();
      }}
    });
    Theme.get("/theme/website/" + webpage.src)
      .then(async theme => {
        const usersThemes = await Theme.getUsers("/theme/user/all-v2");
        themeContent.append(
          ...usersThemes
            .map(theme => Theme.createColor(theme, themeSelect))
        );
        for (const themeOption of themeContent.children) {
          if (themeOption.dataset.value !== theme.src) continue;
          themeSelect.value = themeOption.dataset.value;
          themeLabel.innerText = themeOption.innerText;
          break;
        }
        themeSelect.addEventListener("change", async () => {
          const themeChangeResponse = AJAX.patch("/page/", JSONHandler(), {
            body: JSON.stringify({
              id: webpage.ID,
              property: "themesSRC",
              value: themeSelect.dataset.value.substring(themeSelect.dataset.value.length - 8)
            })
          });
          if (themeChangeResponse.error !== undefined) {
            console.log(themeChangeResponse);
            return;
          }
          validated(themeSelect);
          await Theme.setAsLink(themeSelect.dataset.value);
          for (const themeOption of themeContent.children) {
            if (themeOption.dataset.value === themeSelect.dataset.value) {
              themeLabel.innerText = themeOption.innerText;
              break;
            }
          }
        });
      });
    if (this.#hasAddedThemeSelectShrinkListener === false) {
      window.addEventListener("click", () => themeSelect.classList.remove("expand"));
      this.#hasAddedThemeSelectShrinkListener = true;
    }
    return [
      TitleInspector("Website"),
      HRInspector(),
      TitleInspector("Visibility"),
      RadioGroupInspector(async (value, parentElement) => {
        if (value === "planned") {
          const releaseDateString = releaseDateInput.valueAsDate !== null
            ? releaseDateInput.valueAsDate.toISOString()
            : undefined;
          const plannedResponse = await AJAX.patch("/page/visibility/planned", JSONHandler(), {
            body: JSON.stringify({
              id: webpage.ID,
              releaseDate: releaseDateString
            })
          });
          if (plannedResponse.error || plannedResponse.rowCount !== 1) {
            rejected(parentElement);
            return false;
          }
          validated(parentElement);
          releaseDate.classList.remove("display-none");
          this.#json.webpage.releaseDate = (releaseDateInput.valueAsDate !== null ? releaseDateInput.valueAsDate : new Date()).toISOString();
          this.dispatchJSONEvent();
          return true;
        }
        const visibilityResponse = await AJAX.patch(`/page/visibility/${value}`, JSONHandler(), {
          body: JSON.stringify({
            id: webpage.ID
          })
        });
        if (visibilityResponse.error) {
          rejected(parentElement);
          console.log(visibilityResponse);
          return false;
        }
        validated(parentElement);
        releaseDate.classList.add("display-none");
        this.#json.webpage.releaseDate = undefined;
        this.#json.webpage.isPublic = value === "public";
        this.dispatchJSONEvent();
        return true;
      }, selectOption([
        { text: "Public", value: "public" },
        { text: "Private", value: "private" },
        { text: "Planned", value: "planned" },
      ], this.#json.webpage.isPublic
        ? "public"
        : this.#json.webpage.releaseDate !== undefined
          ? "planned"
          : "private")),
      releaseDate,
      HRInspector(),
      TitleInspector("Properties"),
      TextFieldInspector(this.#json.webpage.title, async (value, parent) => {
        if (value.length < 1 || value.length > 64) {
          rejected(parent);
          return false;
        }
        const response = await AJAX.patch("/page/", JSONHandler(), {
          body: JSON.stringify({
            id: webpage.ID,
            property: "title",
            value
          })
        });
        if (response.error !== undefined) {
          rejected(parent);
          console.log(response);
          return false;
        }
        validated(parent);
        this.#json.webpage.title = value;
        this.dispatchJSONEvent();
        return true;
      }, "Title:"),
      CheckboxInspector(this.#json.webpage.isHomePage, async (value, parentElement) => {
        const setHomePageResponse = await AJAX.patch(`/page/home-page/${webpage.ID}/${Number(value)}`, JSONHandler());
        if (setHomePageResponse.error !== undefined) {
          console.log(setHomePageResponse);
          rejected(parentElement);
          return false;
        }
        this.#json.webpage.isHomePage = value;
        validated(parentElement);
        return true;
      }, "Set as home"),
      CheckboxInspector(this.#json.areCommentsAvailable, (value) => {
        this.#json.areCommentsAvailable = value;
        this.dispatchJSONEvent();
        return true;
      }, "Enable comments"),
      CheckboxInspector(this.#json.isHeaderIncluded, value => {
        this.#json.isHeaderIncluded = value;
        this.dispatchJSONEvent();
        headerSettings.forEach(element => element.classList.toggle("display-none", !value));
        return true;
      }, "Include Header"),
      ...headerSettings,
      HRInspector(),
      TitleInspector("ThemeType"),
      themeSelect,
    ];
  }
  #hasAddedThemeSelectShrinkListener = false;
  save () {
    return {
      type: "WRoot",
      isHeaderIncluded: this.#json.isHeaderIncluded,
      areCommentsAvailable: this.#json.areCommentsAvailable,
      headerTitleAlign: this.#json.headerTitleAlign,
      headerTitleColor: this.#json.headerTitleColor,
      children: this.page.saveChildren()
    };
  }
  allowsDragAndDrop() {
    return false;
  }
  isSelectAble() {
    return false;
  }
  isSelectionPropagable() {
    return false;
  }
  remove () {
    console.error("WRoot cannot be removed.");
  }
}
widgets.define("WRoot", WRoot);
