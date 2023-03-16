class WCommand extends Widget {
  constructor (json, parent, editable = false) {
    super(Span("w-command show-hint", json?.text), parent, editable);
    this.childSupport = "none";
    if (editable === false) {
      return;
    }
    this.rootElement.setAttribute("contenteditable", "true");
    this.rootElement.setAttribute("spellcheck", "false");
    let doRemoveOnNextDelete = true;
    this.rootElement.addEventListener("keyup", evt => {
      if (doRemoveOnNextDelete === true && (evt.key === "Backspace" || evt.key === "Delete")) {
        this.remove();
      }
      if (this.rootElement.textContent === "") {
        this.rootElement.classList.add("show-hint");
        widgetSelect.style.visibility = "hidden";
        doRemoveOnNextDelete = true;
        return;
      }
      doRemoveOnNextDelete = false;
      this.rootElement.classList.remove("show-hint");
      if (this.rootElement.textContent === "/" && evt.key === "/") {
        moveWidgetSelect(this.rootElement);
        setSearchMode(false);
        return;
      }
      const parsedCommand = WCommand.#parseCommand(this.rootElement.textContent);
      if (parsedCommand === "") {
        setSearchMode(false);
        return;
      }
      setSearchMode(true);
      let satisfactoryCountGlobal = 0;
      let satisfactoryWidgetGlobal = undefined;
      for (const category of widgetSelect.children) {
        let satisfactoryCount = 0;
        for (const widget of category.children[1].children) {
          if (!WCommand.#satisfiesSearch(widget.dataset.search, parsedCommand)) {
            widget.classList.remove("search-satisfactory");
            continue;
          }
          widget.classList.add("search-satisfactory");
          satisfactoryCount++;
          satisfactoryWidgetGlobal = widget;
          satisfactoryCountGlobal++;
        }
        if (satisfactoryCount !== 0) {
          category.classList.remove("not-search-satisfactory");
          continue;
        }
        category.classList.add("not-search-satisfactory");
      }
      if (satisfactoryCountGlobal === 0) {
        widgetSelect.classList.add("no-results");
      } else {
        widgetSelect.classList.remove("no-results");
        if (satisfactoryCountGlobal === 1) {
          widgetSelect.querySelectorAll(".widget-option").forEach(w => w.classList.remove("selected"));
          selectedWidget = satisfactoryWidgetGlobal;
          selectedWidget.classList.add("selected");
        }
      }
    });
    this.rootElement.addEventListener("keydown", evt => {
      if (evt.key === "ArrowUp") {
        moveSelection(true);
        evt.preventDefault();
        return;
      }
      if (evt.key === "ArrowDown") {
        moveSelection(false);
        evt.preventDefault();
        return;
      }
      if (evt.key === "Enter") {
        if (this.rootElement.textContent[0] === "/") {
          evt.preventDefault();
          if (selectedWidget === undefined) {
            return;
          }
          if (!selectedWidget.classList.contains("search-satisfactory") && isInSearchMode) {
            return;
          }
          const defaultWidget = widgets.get(selectedWidget.dataset.class).default(this.parentWidget, true);
          this.parentWidget.insertBeforeWidget(defaultWidget, this);
          unfollowWidgetSelect();
          this.remove();
          setSearchMode(false);
          widgetSelect.style.visibility = "hidden";
          defaultWidget.focus();
          defaultWidget.rootElement.scrollIntoView({behavior: "smooth"});
          return;
        }
        evt.preventDefault();
        const lines = [this.rootElement.textContent];
        if (this.rootElement.textContent !== "") {
          lines.push("");
        }
        const textWidget = WText.build({
          type: "WText",
          textEditor: {
            content: lines,
            mode: "simple"
          }
        }, this.parentWidget, true);
        this.parentWidget.insertBeforeWidget(textWidget, this);
        unfollowWidgetSelect();
        this.remove();
        textWidget.focus();
        textWidget.rootElement.scrollIntoView({behavior: "smooth"});
      }
    });
  }
  static #words = ["a","aaenean","ac","accumsan","accumsanmaecenas","acduis","acin","ad","adipiscing","aenean","aliquam","aliquammauris","aliquet","amet","ante","antein","aptent","arcu","at","atsuspendisse","auctor","augue","aut","bibendum","blandit","blanditaliquam","blanditin","blanditsuspendisse","class","commodo","commodonulla","condimentum","congue","consectetur","consequat","consequataliquam","conubia","convallis","convallisaliquam","cras","cubilia","curabitur","curae;","curae;aliquam","cursus","cursussed","dapibus","diam","diammaecenas","dictum","dictumst","dictumstpraesent","dignissim","dignissimnunc","dis","dolor","donec","dui","duiinteger","duiquisque","duis","duised","duiut","efficitur","efficiturnam","egestas","eget","eleifend","eleifendvivamus","elementum","elementumaenean","elementumpraesent","elementumsuspendisse","elit","elitdonec","elitpellentesque","elitphasellus","elitquisque","elitut","enim","erat","eros","erosdonec","est","estcras","estetiam","estnam","estnunc","et","etetiam","etiam","etsed","eu","euaenean","euismod","euismodaliquam","eunam","euut","ex","expellentesque","facilisi","facilisialiquam","facilisis","facilisisnunc","fames","faucibus","faucibuscurabitur","faucibuspellentesque","felis","felisetiam","fermentum","fermentumphasellus","feugiat","finibus","finibusquisque","fringilla","fringilladonec","fringillainteger","fusce","gravida","habitant","habitasse","hac","hendrerit","hendreritphasellus","himenaeos","iaculis","id","idsed","imperdiet","in","inceptos","integer","interdum","interdumetiam","interdumvestibulum","invivamus","ipsum","ipsumdonec","ipsumquisque","justo","justopraesent","lacinia","lacus","laoreet","laoreetmauris","lectus","lectusmaecenas","lectussuspendisse","leo","leofusce","leout","libero","ligula","ligulanunc","litora","lobortis","lobortissed","lorem","lorempellentesque","loremsed","loremsuspendisse","luctus","maecenas","magna","magnaaliquam","magnapellentesque","magnis","malesuada","massa","massacurabitur","massalorem","mattis","mauris","maximus","metus","metusdonec","metusetiam","metusin","metusquisque","mi","minulla","molestie","molestieaenean","molestiecurabitur","molestienullam","mollis","montes","morbi","mus","musaliquam","musdonec","nam","nascetur","natoque","nec","necduis","neque","netus","nibh","nibhduis","nibhin","nisi","nisicras","nisl","non","nostra","nulla","nullam","nunc","nuncetiam","odio","odioetiam","odiosed","orci","orcised","ornare","parturient","pellentesque","pellentesquecras","penatibus","per","pharetra","pharetraduis","phasellus","placerat","placerataliquam","platea","porta","portacurabitur","porttitor","porttitorsed","posuere","potenti","praesent","pretium","primis","proin","pulvinar","pulvinarsed","purus","purusnunc","purussuspendisse","quam","quamcurabitur","quis","quisque","rhoncus","ridiculus","risus","risusnunc","rutrum","rutrumduis","sagittis","sagittissed","sapien","sapienvivamus","scelerisque","scelerisqueaenean","scelerisquesuspendisse","sed","sedquisque","sedut","sem","semper","semperinterdum","senectus","sit","sociosqu","sodales","sollicitudin","suscipit","suspendisse","taciti","tellus","tellusduis","tempor","tempordonec","tempornullam","tempus","tempusdonec","tincidunt","tinciduntcurabitur","tinciduntinteger","tinciduntnunc","tinciduntpraesent","torquent","tortor","tortorsed","tristique","tristiqueaenean","tristiquesed","turpis","turpisdonec","turpissed","turpisvestibulum","ullamcorper","ullamcorperquisque","ultrices","ultricespellentesque","ultricies","ultriciescurabitur","ultriciesetiam","ultriciespraesent","urna","urnapraesent","ut","utinterdum","varius","variusvestibulum","vehicula","vehiculaetiam","vel","velit","velitaliquam","velitnullam","venenatis","venenatisaliquam","venenatisphasellus","vestibulum","vitae","vivamus","viverra","viverramauris","viverramorbi","viverranunc","volutpat","volutpatcras","vulputate","vulputatemauris","vulputatenulla"];
  static #parseCommand (command) {
    return command.substring(1);
  }
  static #satisfiesSearch (index, query) {
    query = query.toLowerCase();
    let queryPointer = 0;
    for (const char of index.toLowerCase()) {
      if (char === query[queryPointer]) {
        queryPointer++;
      }
      if (queryPointer === query.length) {
        return true;
      }
    }
    return false;
  }
  static default (parent, editable = false) {
    return new WCommand({}, parent, editable);
  }
  static build (json, parent, editable = false) {
    if (editable === false) {
      return WText.build({
        type: "WText",
        textEditor: {
          content: [json.text ?? ""],
          mode: "simple"
        }
      }, parent, false);
    }
    return new WCommand(json, parent, true);
  }
  get inspectorHTML () {
    return (
      NotInspectorAble()
    );
  }
  save () {
    return {
      type: "WCommand",
      text: this.rootElement.textContent
    };
  }
  appendEditGui () {
    console.error("Can not add edit GUI to WCommand, because this object will not be saved.");
  }
  isSelectAble() {
    return false;
  }
  isSelectionPropagable() {
    return false;
  }
  focus() {}
}
widgets.define("WCommand", WCommand);
