class WDivider extends Widget {
  #fillingElement;
  #json;
  #resizeable;
  #container;
  static MAX_AMOUNT = 4096;
  static MIN_AMOUNT = 16;
  constructor (json, parent, editable = false) {
    super(Div(
      "w-divider w-divider-container" + (json?.transparent === true
        ? " transparent"
        : ""),
    ), parent, editable);
    this.childSupport = this.childSupport;
    json.dividerAmount ||= 64;
    this.#container = this.rootElement;
    this.#fillingElement = Div("filling");
    this.rootElement.appendChild(this.#fillingElement);
    if (json.dividerAmount !== undefined) {
      this.rootElement.style.setProperty("--amount", json.dividerAmount + "px");
    }
    if (json.fillPosition !== undefined) {
      this.rootElement.style.setProperty("--fill-position", json.fillPosition);
    }
    if (editable) {
      this.#json = new Observable(json);
      this.#json.onChange(descriptor => {
        this.rootElement.style.height = descriptor.dividerAmount + "px";
      });
      this.#resizeable = new Resizeable(this.rootElement, {axes: "vertical"});
      this.#resizeable.on("resize", (width, height) => {
        const json = this.#json.value;
        json.dividerAmount = clamp(WDivider.MIN_AMOUNT, WDivider.MAX_AMOUNT, height);
        this.#json.value = json;
      });
      this.#resizeable.content.classList.add("w-divider-container");
      this.#container = this.#resizeable.content;
      this.rootElement.classList.remove("w-divider-container");
      this.appendEditGui();
    }
  }
  focus() {
    inspect(this.inspectorHTML, this);
  }
  static default (parent, editable = false) {
    return new WDivider({}, parent, editable);
  }
  static build (json, parent, editable = false) {
    return new WDivider(json, parent, editable);
  }
  #dividerHeightField;
  #fillPositionRadioGroup;
  get inspectorHTML () {
    if (this.#dividerHeightField === undefined || this.#fillPositionRadioGroup === undefined) {
      this.#json.onChange(descriptor => {
        this.#dividerHeightField.querySelector("input").value = String(Math.round(descriptor.dividerAmount));
        this.#fillPositionRadioGroup.classList.toggle("display-none", descriptor.transparent);
        this.rootElement.style.setProperty("--fill-position", descriptor.fillPosition ?? "center");
      });
    }
    this.#dividerHeightField ||= TextFieldInspector(
      String(Math.round(this.#json.value.dividerAmount)),
      (value, parentElement) => {
        const number = +value;
        if (isNaN(number)) {
          return false;
        }
        const json = this.#json.value;
        json.dividerAmount = clamp(WDivider.MIN_AMOUNT, WDivider.MAX_AMOUNT, number);
        this.#json.value = json;
        validated(parentElement);
        return true;
      },
      "Height:",
      "32"
    );
    this.#fillPositionRadioGroup ||= RadioGroupInspector((value, parentElement) => {
      const json = this.#json.value;
      json.fillPosition = value;
      this.#json.value = json;
      validated(parentElement);
      return true;
    }, selectOption([
      {text: "Top", value: "start"},
      {text: "Center", value: "center"},
      {text: "Bottom", value: "end"}
    ], this.#json.value.fillPosition, "center"), "Line position");
    this.#json.dispatch();
    return [
      TitleInspector("Divider"),
      HRInspector(),
      TitleInspector("Properties"),
      this.#dividerHeightField,
      CheckboxInspector(!(this.#json.value.transparent ?? false), value => {
        const json = this.#json.value;
        json.transparent = !value;
        this.#json.value = json;
        this.rootElement.classList.toggle("transparent", !value);
        return true;
      }, "Show line"),
      this.#fillPositionRadioGroup
    ];
  }
  save () {
    return {
      type: "WDivider",
      dividerAmount: this.#json.value.dividerAmount,
      transparent: this.#json.value.transparent,
      fillPosition: this.#json.value.fillPosition
    };
  }
}
widgets.define("WDivider", WDivider);
