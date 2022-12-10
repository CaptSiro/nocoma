var WCommand = class WCommand extends Widget {
  constructor (root, parent) {
    super(root, parent);
    this.childSupport = "none";
  }
  static #words = ["a","aaenean","ac","accumsan","accumsanmaecenas","acduis","acin","ad","adipiscing","aenean","aliquam","aliquammauris","aliquet","amet","ante","antein","aptent","arcu","at","atsuspendisse","auctor","augue","aut","bibendum","blandit","blanditaliquam","blanditin","blanditsuspendisse","class","commodo","commodonulla","condimentum","congue","consectetur","consequat","consequataliquam","conubia","convallis","convallisaliquam","cras","cubilia","curabitur","curae;","curae;aliquam","cursus","cursussed","dapibus","diam","diammaecenas","dictum","dictumst","dictumstpraesent","dignissim","dignissimnunc","dis","dolor","donec","dui","duiinteger","duiquisque","duis","duised","duiut","efficitur","efficiturnam","egestas","eget","eleifend","eleifendvivamus","elementum","elementumaenean","elementumpraesent","elementumsuspendisse","elit","elitdonec","elitpellentesque","elitphasellus","elitquisque","elitut","enim","erat","eros","erosdonec","est","estcras","estetiam","estnam","estnunc","et","etetiam","etiam","etsed","eu","euaenean","euismod","euismodaliquam","eunam","euut","ex","expellentesque","facilisi","facilisialiquam","facilisis","facilisisnunc","fames","faucibus","faucibuscurabitur","faucibuspellentesque","felis","felisetiam","fermentum","fermentumphasellus","feugiat","finibus","finibusquisque","fringilla","fringilladonec","fringillainteger","fusce","gravida","habitant","habitasse","hac","hendrerit","hendreritphasellus","himenaeos","iaculis","id","idsed","imperdiet","in","inceptos","integer","interdum","interdumetiam","interdumvestibulum","invivamus","ipsum","ipsumdonec","ipsumquisque","justo","justopraesent","lacinia","lacus","laoreet","laoreetmauris","lectus","lectusmaecenas","lectussuspendisse","leo","leofusce","leout","libero","ligula","ligulanunc","litora","lobortis","lobortissed","lorem","lorempellentesque","loremsed","loremsuspendisse","luctus","maecenas","magna","magnaaliquam","magnapellentesque","magnis","malesuada","massa","massacurabitur","massalorem","mattis","mauris","maximus","metus","metusdonec","metusetiam","metusin","metusquisque","mi","minulla","molestie","molestieaenean","molestiecurabitur","molestienullam","mollis","montes","morbi","mus","musaliquam","musdonec","nam","nascetur","natoque","nec","necduis","neque","netus","nibh","nibhduis","nibhin","nisi","nisicras","nisl","non","nostra","nulla","nullam","nunc","nuncetiam","odio","odioetiam","odiosed","orci","orcised","ornare","parturient","pellentesque","pellentesquecras","penatibus","per","pharetra","pharetraduis","phasellus","placerat","placerataliquam","platea","porta","portacurabitur","porttitor","porttitorsed","posuere","potenti","praesent","pretium","primis","proin","pulvinar","pulvinarsed","purus","purusnunc","purussuspendisse","quam","quamcurabitur","quis","quisque","rhoncus","ridiculus","risus","risusnunc","rutrum","rutrumduis","sagittis","sagittissed","sapien","sapienvivamus","scelerisque","scelerisqueaenean","scelerisquesuspendisse","sed","sedquisque","sedut","sem","semper","semperinterdum","senectus","sit","sociosqu","sodales","sollicitudin","suscipit","suspendisse","taciti","tellus","tellusduis","tempor","tempordonec","tempornullam","tempus","tempusdonec","tincidunt","tinciduntcurabitur","tinciduntinteger","tinciduntnunc","tinciduntpraesent","torquent","tortor","tortorsed","tristique","tristiqueaenean","tristiquesed","turpis","turpisdonec","turpissed","turpisvestibulum","ullamcorper","ullamcorperquisque","ultrices","ultricespellentesque","ultricies","ultriciescurabitur","ultriciesetiam","ultriciespraesent","urna","urnapraesent","ut","utinterdum","varius","variusvestibulum","vehicula","vehiculaetiam","vel","velit","velitaliquam","velitnullam","venenatis","venenatisaliquam","venenatisphasellus","vestibulum","vitae","vivamus","viverra","viverramauris","viverramorbi","viverranunc","volutpat","volutpatcras","vulputate","vulputatemauris","vulputatenulla"];
  static default (parent) {
    const command = new WCommand(html({
      name: "p",
      className: ["w-command", "show-hint"],
    }), parent);
    command.rootElement.setAttribute("contenteditable", true);
    command.rootElement.setAttribute("spellcheck", false);
    let doRemoveOnNextDelete = true;
    command.rootElement.addEventListener("keyup", evt => {
      if (doRemoveOnNextDelete == true && (evt.key == "Backspace" || evt.key == "Delete")) {
        command.remove();
      }
      if (command.rootElement.textContent == "") {
        command.rootElement.classList.add("show-hint");
        doRemoveOnNextDelete = true;
        if (command.rootElement.children.length == 0) {
          command.rootElement.append(document.createElement('div'));
        }
        return;
      }
      doRemoveOnNextDelete = false;
      command.rootElement.classList.remove("show-hint");
    });
    return command;
  }
  static build (json, parent, editable = false) {
    return this.default(parent);
  }
  get inspectorJSON () {
    return {
      inspectorAble: false,
    };
  }
  save () {
    return {
      type: "WCommand"
    };
  }
  appendEditGui () {
    console.error("Can not add edit GUI to WCommand, because this object will not be saved.");
  }
};