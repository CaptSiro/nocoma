</div>
  <nav>
    <div class="settings-bar">
      <div class="settings-drop">
        <span class="content">File</span>
        <div class="dropdown">
<!--          <div class="dropdown-item"><span class="content">Undo</span><span class="hint">ctrl + z</span></div>-->
<!--          <div class="dropdown-item"><span class="content">Redo</span><span class="hint">ctrl + shift + z</span></div>-->
          <div class="dropdown-item" onclick="file_save()">
            <span class="content">Save</span>
            <span class="hint">ctrl + s</span>
          </div>
          <div class="dropdown-item" onclick="file_share()">
            <span class="content">Share</span>
          </div>
<!--          <div class="dropdown-item inner-dropdown drop">-->
<!--            <span class="content">Save as</span><span class="hint">ctrl + shift + s</span>-->
<!--            <div class="dropdown">-->
<!--              <div class="dropdown-item"><span class="content">.docx</span></div>-->
<!--              <div class="dropdown-item"><span class="content">.odt</span></div>-->
<!--              <div class="dropdown-item"><span class="content">.pdf</span></div>-->
<!--            </div>-->
<!--          </div>-->
<!--          <div class="dropdown-item">-->
<!--            <span class="content">Delete</span>-->
<!--          </div>-->
          <div class="dropdown-item next-section" onclick="file_open()">
            <span class="content">Open</span>
            <span class="hint">ctrl + o</span>
          </div>
          <div class="dropdown-item" onclick="file_exit()">
            <span class="content">Exit</span>
            <span class="hint">ctrl + e</span>
          </div>
        </div>
      </div>
      <div class="settings-drop">
        <span class="content">Edit</span>
        <div class="dropdown">
          <div class="dropdown-item" id="edit-select-all">
            <span class="content">Select all</span>
            <span class="hint">ctrl + a</span>
          </div>
          <div class="dropdown-item" id="edit-deselect-all">
            <span class="content">Deselect all</span>
            <span class="hint">escape</span>
          </div>
          
          <div class="dropdown-item next-section" id="edit-delete">
            <span class="content">Delete</span>
          </div>
          <div class="dropdown-item" id="edit-copy">
            <span class="content">Copy</span>
            <span class="hint">ctrl + c</span>
          </div>
          <div class="dropdown-item" id="edit-cut">
            <span class="content">Cut</span>
            <span class="hint">ctrl + x</span>
          </div>
          <div class="dropdown-item" id="edit-paste">
            <span class="content">Paste</span>
            <span class="hint">ctrl + v</span>
          </div>
          
          <div class="dropdown-item next-section" id="edit-website-properties"><span class="content">Website properties</span></div>
        </div>
      </div>
<!--      <div class="settings-drop">-->
<!--        <span class="content">Tools</span>-->
<!--        <div class="dropdown">-->
<!--          <div class="dropdown-item"><span class="content">Undo</span><span class="hint">ctrl + z</span></div>-->
<!--          <div class="dropdown-item"><span class="content">Redo</span><span class="hint">ctrl + shift + z</span></div>-->
<!--          <div class="dropdown-item next-section"><span class="content">Save</span><span class="hint">ctrl + s</span></div>-->
<!--          <div class="dropdown-item inner-dropdown drop">-->
<!--            <span class="content">Save as</span><span class="hint">ctrl + shift + s</span>-->
<!--            <div class="dropdown">-->
<!--              <div class="dropdown-item"><span class="content">.docx</span></div>-->
<!--              <div class="dropdown-item"><span class="content">.odt</span></div>-->
<!--              <div class="dropdown-item"><span class="content">.pdf</span></div>-->
<!--            </div>-->
<!--          </div>-->
<!--          <div class="dropdown-item inner-dropdown drop">-->
<!--            <span class="content">Save as</span><span class="hint">ctrl + shift + s</span>-->
<!--            <div class="dropdown">-->
<!--              <div class="dropdown-item"><span class="content">.docx</span></div>-->
<!--              <div class="dropdown-item"><span class="content">.odt</span></div>-->
<!--              <div class="dropdown-item"><span class="content">.pdf</span></div>-->
<!--            </div>-->
<!--          </div>-->
<!--          <div class="dropdown-item inner-dropdown drop">-->
<!--            <span class="content">Save as</span><span class="hint">ctrl + shift + s</span>-->
<!--            <div class="dropdown">-->
<!--              <div class="dropdown-item"><span class="content">.docx</span></div>-->
<!--              <div class="dropdown-item"><span class="content">.odt</span></div>-->
<!--              <div class="dropdown-item"><span class="content">.pdf</span></div>-->
<!--            </div>-->
<!--          </div>-->
<!--          <div class="dropdown-item inner-dropdown drop">-->
<!--            <span class="content">Save as</span><span class="hint">ctrl + shift + s</span>-->
<!--            <div class="dropdown">-->
<!--              <div class="dropdown-item"><span class="content">.docx</span></div>-->
<!--              <div class="dropdown-item"><span class="content">.odt</span></div>-->
<!--              <div class="dropdown-item"><span class="content">.pdf</span></div>-->
<!--            </div>-->
<!--          </div>-->
<!--          <div class="dropdown-item next-section"><span class="content">New</span></div>-->
<!--          <div class="dropdown-item"><span class="content">Delete</span></div>-->
<!--          <div class="dropdown-item"><span class="content">Open</span></div>-->
<!--        </div>-->
<!--      </div>-->
      <div class="settings-drop">
        <span class="content">Help</span>
        <div class="dropdown">
          <div class="dropdown-item"><span class="content">Help</span></div>
        </div>
      </div>
    </div>
  </nav>

  <div class="table">
    <div class="viewport-mount">
      <div id="viewport">
        <div id="widget-select-mount" style="visibility: hidden"></div>
      </div>
      <button class="toggle-viewport">mobile/PC</button>
    </div>

    <div class="inspector">
      <!-- <div class="widgets">
        <div class="search-mount row-layout">
          <input type="text">
          <img src="../../public/images/search.svg" alt="expand" draggable="false">
        </div>
        
        <div class="w-categories">
          <div class="w-category">
            <div class="id">
              <img src="../../public/images/expand.svg" alt="expand" draggable="false">
              <p>Widget category 1</p>
            </div>
  
            <div class="content">
              <div class="widget row-layout">
                <img src="../../public/images/heading.svg" alt="heading">
                <p>Heading</p>
              </div>
              <div class="widget row-layout">
                <img src="../../public/images/paragraph.svg" alt="paragraph">
                <p>Paragraph</p>
              </div>
              <div class="widget row-layout">
                <img src="../../public/images/list.svg" alt="">
                <p>List</p>
              </div>
              <div class="widget row-layout">
                <img src="../../public/images/quote.svg" alt="">
                <p>Quote</p>
              </div>
              <div class="widget row-layout">
                <img src="../../public/images/image.svg" alt="">
                <p>Image</p>
              </div>
              <div class="widget row-layout">
                <img src="../../public/images/download.svg" alt="">
                <p>File download</p>
              </div>
              <div class="widget row-layout">
                <img src="../../public/images/column.svg" alt="">
                <p>Column</p>
              </div>
              <div class="widget row-layout">
                <img src="../../public/images/row.svg" alt="">
                <p>Row</p>
              </div>
              <div class="widget row-layout">
                <img src="../../public/images/" alt="">
                <p>End of page</p>
              </div>
              <div class="widget row-layout">
                <img src="../../public/images/" alt="">
                <p>Divider</p>
              </div>
              <div class="widget row-layout">
                <img src="../../public/images/" alt="">
                <p>Star rating</p>
              </div>
              <div class="widget row-layout">
                <img src="../../public/images/" alt="">
                <p>Timer</p>
              </div>
              <div class="widget row-layout">
                <img src="../../public/images/" alt="">
                <p>Date of post</p>
              </div>
              <div class="widget row-layout">
                <img src="../../public/images/" alt="">
                <p>Post navigation</p>
              </div>
            </div>
          </div>
          
          <div class="w-category">
            <div class="id">
              <img src="../../public/images/expand.svg" alt="expand" draggable="false">
              <p>Widget category 1</p>
            </div>
  
            <div class="content">
              <div class="widget row-layout">
                <i class="icon">&lt;icon&gt;</i>
                <p>Widget 1</p>
              </div>
              <div class="widget row-layout">
                <i class="icon">&lt;icon&gt;</i>
                <p>Widget 2</p>
              </div>
              <div class="widget row-layout">
                <i class="icon">&lt;icon&gt;</i>
                <p>Widget 3</p>
              </div>
            </div>
          </div>
  
          <div class="w-category">
            <div class="id">
              <img src="../../public/images/expand.svg" alt="expand" draggable="false">
              <p>Widget category 1</p>
            </div>
  
            <div class="content">
              <div class="widget row-layout">
                <i class="icon">&lt;icon&gt;</i>
                <p>Widget 1</p>
              </div>
              <div class="widget row-layout">
                <i class="icon">&lt;icon&gt;</i>
                <p>Widget 2</p>
              </div>
              <div class="widget row-layout">
                <i class="icon">&lt;icon&gt;</i>
                <p>Widget 3</p>
              </div>
            </div>
          </div>
  
          <div class="w-category">
            <div class="id">
              <img src="../../public/images/expand.svg" alt="expand" draggable="false">
              <p>Widget category 1</p>
            </div>
  
            <div class="content">
              <div class="widget row-layout">
                <i class="icon">&lt;icon&gt;</i>
                <p>Widget 1</p>
              </div>
              <div class="widget row-layout">
                <i class="icon">&lt;icon&gt;</i>
                <p>Widget 2</p>
              </div>
              <div class="widget row-layout">
                <i class="icon">&lt;icon&gt;</i>
                <p>Widget 3</p>
              </div>
            </div>
          </div>
  
          <div class="w-category">
            <div class="id">
              <img src="../../public/images/expand.svg" alt="expand" draggable="false">
              <p>Widget category 1</p>
            </div>
  
            <div class="content">
              <div class="widget row-layout">
                <i class="icon">&lt;icon&gt;</i>
                <p>Widget 1</p>
              </div>
              <div class="widget row-layout">
                <i class="icon">&lt;icon&gt;</i>
                <p>Widget 2</p>
              </div>
              <div class="widget row-layout">
                <i class="icon">&lt;icon&gt;</i>
                <p>Widget 3</p>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="resize-divider"></div>
      <div class="tree-structure"></div> -->
    </div>
  </div>
</body>
</html>