class WCommand extends Widget { // var is used because it creates reference on globalThis (window) object

  // use json.child for single child widget like Center
  // or json.children for array of widgets
  /**
   * @typedef CommandJSONType
   * 
   * @typedef {CommandJSONType & WidgetJSON} CommandJSON
   */
  
  /**
   * @param {CommandJSON} json
   * @param {Widget} parent
   * @param {boolean} editable
   */
  constructor (json, parent, editable = false) {
    super(Span("w-command show-hint"), parent, editable);
    this.childSupport = "none";
  }

  //TODO: lorem ipsum generator
  static #words = ["a","aaenean","ac","accumsan","accumsanmaecenas","acduis","acin","ad","adipiscing","aenean","aliquam","aliquammauris","aliquet","amet","ante","antein","aptent","arcu","at","atsuspendisse","auctor","augue","aut","bibendum","blandit","blanditaliquam","blanditin","blanditsuspendisse","class","commodo","commodonulla","condimentum","congue","consectetur","consequat","consequataliquam","conubia","convallis","convallisaliquam","cras","cubilia","curabitur","curae;","curae;aliquam","cursus","cursussed","dapibus","diam","diammaecenas","dictum","dictumst","dictumstpraesent","dignissim","dignissimnunc","dis","dolor","donec","dui","duiinteger","duiquisque","duis","duised","duiut","efficitur","efficiturnam","egestas","eget","eleifend","eleifendvivamus","elementum","elementumaenean","elementumpraesent","elementumsuspendisse","elit","elitdonec","elitpellentesque","elitphasellus","elitquisque","elitut","enim","erat","eros","erosdonec","est","estcras","estetiam","estnam","estnunc","et","etetiam","etiam","etsed","eu","euaenean","euismod","euismodaliquam","eunam","euut","ex","expellentesque","facilisi","facilisialiquam","facilisis","facilisisnunc","fames","faucibus","faucibuscurabitur","faucibuspellentesque","felis","felisetiam","fermentum","fermentumphasellus","feugiat","finibus","finibusquisque","fringilla","fringilladonec","fringillainteger","fusce","gravida","habitant","habitasse","hac","hendrerit","hendreritphasellus","himenaeos","iaculis","id","idsed","imperdiet","in","inceptos","integer","interdum","interdumetiam","interdumvestibulum","invivamus","ipsum","ipsumdonec","ipsumquisque","justo","justopraesent","lacinia","lacus","laoreet","laoreetmauris","lectus","lectusmaecenas","lectussuspendisse","leo","leofusce","leout","libero","ligula","ligulanunc","litora","lobortis","lobortissed","lorem","lorempellentesque","loremsed","loremsuspendisse","luctus","maecenas","magna","magnaaliquam","magnapellentesque","magnis","malesuada","massa","massacurabitur","massalorem","mattis","mauris","maximus","metus","metusdonec","metusetiam","metusin","metusquisque","mi","minulla","molestie","molestieaenean","molestiecurabitur","molestienullam","mollis","montes","morbi","mus","musaliquam","musdonec","nam","nascetur","natoque","nec","necduis","neque","netus","nibh","nibhduis","nibhin","nisi","nisicras","nisl","non","nostra","nulla","nullam","nunc","nuncetiam","odio","odioetiam","odiosed","orci","orcised","ornare","parturient","pellentesque","pellentesquecras","penatibus","per","pharetra","pharetraduis","phasellus","placerat","placerataliquam","platea","porta","portacurabitur","porttitor","porttitorsed","posuere","potenti","praesent","pretium","primis","proin","pulvinar","pulvinarsed","purus","purusnunc","purussuspendisse","quam","quamcurabitur","quis","quisque","rhoncus","ridiculus","risus","risusnunc","rutrum","rutrumduis","sagittis","sagittissed","sapien","sapienvivamus","scelerisque","scelerisqueaenean","scelerisquesuspendisse","sed","sedquisque","sedut","sem","semper","semperinterdum","senectus","sit","sociosqu","sodales","sollicitudin","suscipit","suspendisse","taciti","tellus","tellusduis","tempor","tempordonec","tempornullam","tempus","tempusdonec","tincidunt","tinciduntcurabitur","tinciduntinteger","tinciduntnunc","tinciduntpraesent","torquent","tortor","tortorsed","tristique","tristiqueaenean","tristiquesed","turpis","turpisdonec","turpissed","turpisvestibulum","ullamcorper","ullamcorperquisque","ultrices","ultricespellentesque","ultricies","ultriciescurabitur","ultriciesetiam","ultriciespraesent","urna","urnapraesent","ut","utinterdum","varius","variusvestibulum","vehicula","vehiculaetiam","vel","velit","velitaliquam","velitnullam","venenatis","venenatisaliquam","venenatisphasellus","vestibulum","vitae","vivamus","viverra","viverramauris","viverramorbi","viverranunc","volutpat","volutpatcras","vulputate","vulputatemauris","vulputatenulla"];
  
  
  /**
   * @param {string} command
   */
  static #parseCommand (command) {
    return command.substring(1);
  }
  
  
  /**
   * @param {string} index
   * @param {string} query
   * @returns {boolean}
   */
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
  
  
  /**
   * @override
   * @param {Widget} parent
   * @param {boolean} editable
   * @returns {WCommand}
   */
  static default (parent, editable = false) {
    const command = new WCommand({}, parent);

    command.rootElement.setAttribute("contenteditable", "true");
    command.rootElement.setAttribute("spellcheck", "false");

    let doRemoveOnNextDelete = true;
    
    command.rootElement.addEventListener("keyup", evt => {
      if (doRemoveOnNextDelete === true && (evt.key === "Backspace" || evt.key === "Delete")) {
        command.remove();
      }

      if (command.rootElement.textContent === "") {
        command.rootElement.classList.add("show-hint");
        widgetSelect.style.visibility = "hidden";
        doRemoveOnNextDelete = true;

        return;
      }
  
      doRemoveOnNextDelete = false;
      command.rootElement.classList.remove("show-hint");
      
      if (command.rootElement.textContent === "/" && evt.key === "/") {
        moveWidgetSelect(command.rootElement);
        return;
      }
  
      const parsedCommand = this.#parseCommand(command.rootElement.textContent);
      
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
          if (!this.#satisfiesSearch(widget.dataset.search, parsedCommand)) {
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
    
    command.rootElement.addEventListener("keydown", evt => {
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
        if (command.rootElement.textContent[0] === "/") {
          evt.preventDefault();
          
          if (selectedWidget === undefined) {
            return;
          }
          
          if (!selectedWidget.classList.contains("search-satisfactory") && isInSearchMode) {
            return;
          }
          
          const defaultWidget = widgets.get(selectedWidget.dataset.class).default(command.parentWidget, true);
          command.parentWidget.insertBeforeWidget(defaultWidget, command);
          command.remove();

          setSearchMode(false);
          widgetSelect.style.visibility = "hidden";
          defaultWidget.focus();
          
          return;
        }
        
        evt.preventDefault();
        
        const lines = [command.rootElement.textContent];
        if (command.rootElement.textContent !== "") {
          lines.push("");
        }
        
        const textWidget = WText.build({
          type: "WText",
          textEditor: {
            content: lines,
            mode: "simple"
          }
        }, command.parentWidget, true);
        
        command.parentWidget.insertBeforeWidget(textWidget, command);
        command.remove();
        
        textWidget.focus();
      }
    });

    return command;
  }

  /**
   * @override
   * @param {CommandJSON} json
   * @param {Widget} parent
   * @param {boolean} editable
   * @returns {WCommand}
   */
  static build (json, parent, editable = false) {
    return this.default(parent);
  }

  /**
   * @override
   * @returns {ComponentContent}
   */
  get inspectorHTML () {
    return (
      NotInspectorAble()
    );
  }

  /**
   * @override
   * @returns {WidgetJSON}
   */
  save () {
    //TODO: when content => file_save as text (more on Notion)
    return {
      type: "WCommand"
    };
  }

  /**
   * @override
   */
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