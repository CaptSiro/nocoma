class WCommentSection extends Widget {
  constructor (json, parent, editable = false) {
    super(Section("w-comment-section"), parent);
    this.childSupport = this.childSupport;
    if (document.querySelector("#arrow-def") === null) {
      `<svg>
        <defs>
          <g id="svg-arrow">
            <g>
              <polygon fill="currentColor" points="411.8,250.1 87.7,450.4 87.7,391.8 364.3,220.7 	"/>
            </g>
            <g>
              <polygon fill="currentColor" points="87.7,49.7 411.8,250 364.3,279.3 87.7,108.2 	"/>
            </g>
            <polygon fill="currentColor" points="411.8,250 87.7,450.4 87.7,406.5 346.6,250 "/>
            <polygon fill="currentColor" points="87.7,49.7 411.7,250 340.3,249.8 87.7,91.7 "/>
          </g>
        </defs>
      </svg>`
      const svg = Component("svg", __, HTML(`
        <defs>
          <g id="svg-arrow">
            <g>
              <polygon fill="currentColor" points="411.8,250.1 87.7,450.4 87.7,391.8 364.3,220.7 	"/>
            </g>
            <g>
              <polygon fill="currentColor" points="87.7,49.7 411.8,250 364.3,279.3 87.7,108.2 	"/>
            </g>
            <polygon fill="currentColor" points="411.8,250 87.7,450.4 87.7,406.5 346.6,250 "/>
            <polygon fill="currentColor" points="87.7,49.7 411.7,250 340.3,249.8 87.7,91.7 "/>
          </g>
        </defs>
      `));
      svg.id = "arrow-def";
      svg.classList.add("display-none");
      document.body.insertBefore(svg, document.body.childNodes[0]);
    }
    this.rootElement.append(
      Div("comments-count", [
        "Comments: ",
        Async(async () => {
          return Span(__, await AJAX.get("/comments/count/" + webpage.ID, TextHandler(), {}, AJAX.SERVER_HOME));
        }, Span(__, "..."))
      ])
    )
    if (editable === true) {
      this.rootElement.append(
        CommentForm(this, true),
        Comment({
          content: [["Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Duis sapien nunc, commodo et, interdum suscipit, sollicitudin et, dolor. Fusce suscipit libero eget elit. Nunc auctor. Pellentesque ipsum. Integer vulputate sem a nibh rutrum consequat. In dapibus augue non sapien. Nullam at arcu a est sollicitudin euismod. Aliquam erat volutpat. Fusce wisi. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos hymenaeos. Vestibulum fermentum tortor id mi. Aenean fermentum risus id tortor."]],
          dateAdded: Date.now(),
          isTopLevel: true,
          reactionCount: 50,
          ID: 0,
          username: "User_1",
          usersID: 0,
          timePosted: Date.now() - (60 * 60 * 24 * 7 * 1000),
          creatorID: 9,
          level: 0,
          isPinned: true,
          reaction: 0
        }, this)
      );
      return;
    }
    if (!webpage.areCommentsAvailable) {
      this.rootElement.classList.add("display-none");
      return;
    }
  }
  static default (parent, editable = false) {
    return new WCommentSection({}, parent, editable);
  }
  static build (json, parent, editable = false) {
    return new WCommentSection({}, parent, editable);
  }
  get inspectorHTML () {
    return (
      NotInspectorAble()
    );
  }
  save () {
    return {
      type: "WCommentSection"
    };
  }
  remove () {
    console.error("WCommentSection cannot be removed.");
  }
}
widgets.define("WCommentSection", WCommentSection);
function Comment (comment, context) {
  const content = WTextEditor.build({
    content: comment.content,
    mode: "fancy"
  }, context, false);
  const id = guid(true);
  const messageBox = content.rootElement.querySelector("article");
  messageBox.id = id;
  let expand = true;
  const seeMoreButton = (
    Button("see-more display-none", "(see more)", evt => {
      content.rootElement.classList.toggle("expand", expand);
      evt.target.textContent = expand
        ? "(see less)"
        : "(see more)"
      expand = !expand;
    })
  );
  untilElement("#" + messageBox.id)
    .then(() => {if (messageBox.offsetHeight < messageBox.scrollHeight) {
        seeMoreButton.classList.remove("display-none");
        return;
      }
      content.rootElement.classList.add("expand");
      delete messageBox.id;
      freeID(id);
    });
  return (
    Div("comment " + (comment.isPinned ? "pinned" : ""), [
      Div("published", [
        Div("profile-picture", [
          Img(AJAX.SERVER_HOME + "/profile/picture/" + comment.ID, "pfp")
        ]),
        Div("text-content expand-able", [
          Div("column", [
            Div("date", formatDate(comment.timePosted)),
            Div("username", [
              Heading(3, __, comment.username),
              Div("tags", [
                OptionalComponent(comment.level === 0,
                  Div("admin", "Admin")
                ),
                OptionalComponent(comment.creatorID === comment.usersID,
                  Div("creator", "Creator")
                ),
              ])
            ]),
          ]),
          content.rootElement,
          seeMoreButton
        ])
      ]),
      Div("comment-controls", [
        Div("start", [
          Button("arrow", Span(__, "∧"), evt => {
            const counter = evt.target.nextElementSibling;
            counter.textContent = String(+counter.dataset.count + 1);
          }),
          Div(__, String(comment.reactionCount), { attributes: { "data-count": comment.reactionCount } }),
          Button("arrow", Span(__, "∨"), evt => {
          }),
          ...OptionalComponents(comment.isTopLevel, [
            Div("separator"),
            Button(__, "Reply", evt => {
            }),
            Button(__, "See replies", evt => {
            }),
          ])
        ]),
        Div("end", [
          OptionalComponent(user.level === 0 || comment.creatorID === user.ID || user.ID === comment.usersID,
            Button("delete", "Remove", evt => {
            })
          ),
          OptionalComponent(comment.creatorID === user.ID && comment.isTopLevel,
            Button("star-container circular", Div("star"), evt => {
              comment.isPinned = !comment.isPinned;
              console.log(comment.isPinned ? "pinned" : "unpinned");
              evt.target.closest(".comment").classList.toggle("pinned", comment.isPinned)
            })
          )
        ])
      ]),
    ])
  );
}
function CommentForm (context, isJustForShow = false) {
  const comment = {
    content: []
  }
  const content = WTextEditor.build({
    content: comment.content,
    mode: "fancy",
    hint: "Write a comment..."
  }, context, true);
  return (
    Div("comment reply", [
      Div("published", [
        Div("profile-picture", [
          Img(AJAX.SERVER_HOME + "/profile/picture/", "pfp")
        ]),
        Div("text-content expand expand-able", [
          Div("column", [
            Div("username", [
              Heading(3, __, user.username),
              Div("tags", [
                OptionalComponent(user.level === 0,
                  Div("admin", "Admin")
                ),
                OptionalComponent(webpage.usersID === user.ID,
                  Div("creator", "Creator")
                ),
              ])
            ]),
          ]),
          content.rootElement
        ])
      ]),
      Div("comment-controls", [
        Div(),
        Div("end", [
          Button("delete", "Remove", evt => {
          }),
          Button("submit", "Submit", evt => {
          }),
        ])
      ]),
    ])
  );
}
