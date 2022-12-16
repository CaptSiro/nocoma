<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin - NoComa</title>
  
  <link rel="stylesheet" href="<?=$GLOBALS["__HOME__"]?>/public/css/main.css">
  <link rel="stylesheet" href="<?=$GLOBALS["__HOME__"]?>/public/css/dashboard.css">
  <link rel="stylesheet" href="<?=$GLOBALS["__HOME__"]?>/public/css/admin-dashboard.css">
  
  <script src="<?=$GLOBALS["__HOME__"]?>/public/js/main.js"></script>
  <script>
    AJAX.DOMAIN_HOME = "<?=$GLOBALS["__HOME__"]?>";
    AJAX.SERVER_HOME = "<?=$GLOBALS["__SERVER_HOME__"] ?? $GLOBALS["__HOME__"]?>";
  </script>
  <script src="<?=$GLOBALS["__HOME__"]?>/public/js/grid.js" defer></script>
  <script src="<?=$GLOBALS["__HOME__"]?>/public/js/dashboard.js" defer></script>
  <script src="<?=$GLOBALS["__HOME__"]?>/public/js/dashboard-admin.js" defer></script>
</head>
<body>
  <div class="modals">
    <div class="windows form">
    
    </div>
  </div>
  
  <nav>
    <section>
      <button reference-to="users" class="link-pointer active" title="Users"><span>1</span></button>
      <button reference-to="appeals" class="link-pointer" title="Appeals"><span>2</span></button>
    </section>
    <section>
      <button class="logout" title="Logout" id="logout"><span style="font-family: monospace;">&lt;-</span></button>
    </section>
  </nav>
  
  <main>
    <div columns="4" class="c-grid link-element" auto-fill="true" id="users">
      <section column="0-4" row="0-4">
        <header class="no-mg">
          <section>
            <button>Banned</button>
            <button>Planned</button>
          </section>
          <section class="search-mount">
            <img src="<?=$GLOBALS["__HOME__"]?>/public/images/search-white.svg" alt="search">
            <input type="text">
          </section>
        </header>
        
        <div class="scrollable user-view no-mg">
          <div class="user expanded">
            <div class="u-head">
              <div class="start">
                <img src="<?=$GLOBALS["__HOME__"]?>/public/images/expand.svg" alt="expand" class="expand">
                <img src="<?=$GLOBALS["__HOME__"]?>/public/images/stock/pfp.png" alt="pfp">
                <div class="u-column">
                  <h4>Username</h4>
                  <span>website</span>
                </div>
              </div>
              <div class="end">
                <div class="option-mount">
                  <div class="visible">
                    <div class="option-mount">
                      <div class="visible">
                        <img src="<?=$GLOBALS["__HOME__"]?>/public/images/options-white.svg" alt="options" class="icon button-like">
                      </div>
                      
                      <div class="menu-body">
                        <div>
                          <span class="label">Choice 1</span>
                        </div>
                        <div>
                          <span class="label">Choice 2</span>
                          <span class="hint">Hint</span>
                        </div>
                        <div>
                          <span class="label">Choice 3</span>
                          <span class="hint">Hint</span>
                        </div>
                        <div>
                          <span class="label">Choice 4</span>
                        </div>
                        <div>
                          <span class="label">Choice 5</span>
                          <span class="hint">Hint</span>
                        </div>
                      </div>
                    </div>
                  </div>
                  
                  <div class="menu-body">
                    <div>
                      <span class="label">Choice 1</span>
                    </div>
                    <div>
                      <span class="label">Choice 2</span>
                      <span class="hint">Hint</span>
                    </div>
                    <div>
                      <span class="label">Choice 3</span>
                      <span class="hint">Hint</span>
                    </div>
                    <div>
                      <span class="label">Choice 4</span>
                    </div>
                    <div>
                      <span class="label">Choice 5</span>
                      <span class="hint">Hint</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="u-posts">
              <div class="post">
                <div class="absolute">
                  <img src="<?=$GLOBALS["__HOME__"]?>/public/images/theme-stock-pictures/__7612349654.png" alt="post img">
                  <div class="darken"></div>
                </div>
                <div class="content">
                  <label class="checkbox-container">
                    <input type="checkbox" name="checkbox" id="checkbox-0">
                    <span></span>
                  </label>
                  <div>
                    <div class="date">01-01-2020</div>
                    <h3>Title of the post</h3>
                  </div>
                </div>
                <div class="option-mount">
                  <div class="visible">
                    <img src="<?=$GLOBALS["__HOME__"]?>/public/images/options-white.svg" alt="options" class="icon button-like">
                  </div>
                  
                  <div class="menu-body">
                    <div>
                      <span class="label">Choice 1</span>
                    </div>
                    <div>
                      <span class="label">Choice 2</span>
                      <span class="hint">Hint</span>
                    </div>
                    <div>
                      <span class="label">Choice 3</span>
                      <span class="hint">Hint</span>
                    </div>
                    <div>
                      <span class="label">Choice 4</span>
                    </div>
                    <div>
                      <span class="label">Choice 5</span>
                      <span class="hint">Hint</span>
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="post">
                <div class="absolute">
                  <img src="<?=$GLOBALS["__HOME__"]?>/public/images/theme-stock-pictures/__89754345.png" alt="post img">
                  <div class="darken"></div>
                </div>
                <div class="content">
                  <label class="checkbox-container">
                    <input type="checkbox" name="checkbox" id="checkbox-0">
                    <span></span>
                  </label>
                  <div>
                    <div class="date">01-01-2020</div>
                    <h3>Title of the post</h3>
                  </div>
                </div>
                <div class="option-mount">
                  <div class="visible">
                    <img src="<?=$GLOBALS["__HOME__"]?>/public/images/options-white.svg" alt="options" class="icon button-like">
                  </div>
                  
                  <div class="menu-body">
                    <div>
                      <span class="label">Choice 1</span>
                    </div>
                    <div>
                      <span class="label">Choice 2</span>
                      <span class="hint">Hint</span>
                    </div>
                    <div>
                      <span class="label">Choice 3</span>
                      <span class="hint">Hint</span>
                    </div>
                    <div>
                      <span class="label">Choice 4</span>
                    </div>
                    <div>
                      <span class="label">Choice 5</span>
                      <span class="hint">Hint</span>
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="post">
                <div class="absolute">
                  <img src="<?=$GLOBALS["__HOME__"]?>/public/images/theme-stock-pictures/__80840328.png" alt="post img">
                  <div class="darken"></div>
                </div>
                <div class="content">
                  <label class="checkbox-container">
                    <input type="checkbox" name="checkbox" id="checkbox-0">
                    <span></span>
                  </label>
                  <div>
                    <div class="date">01-01-2020</div>
                    <h3>Title of the post</h3>
                  </div>
                </div>
                <div class="option-mount">
                  <div class="visible">
                    <img src="<?=$GLOBALS["__HOME__"]?>/public/images/options-white.svg" alt="options" class="icon button-like">
                  </div>
                  
                  <div class="menu-body">
                    <div>
                      <span class="label">Choice 1</span>
                    </div>
                    <div>
                      <span class="label">Choice 2</span>
                      <span class="hint">Hint</span>
                    </div>
                    <div>
                      <span class="label">Choice 3</span>
                      <span class="hint">Hint</span>
                    </div>
                    <div>
                      <span class="label">Choice 4</span>
                    </div>
                    <div>
                      <span class="label">Choice 5</span>
                      <span class="hint">Hint</span>
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="post">
                <div class="absolute">
                  <img src="<?=$GLOBALS["__HOME__"]?>/public/images/theme-stock-pictures/__8219034641.png" alt="post img">
                  <div class="darken"></div>
                </div>
                <div class="content">
                  <label class="checkbox-container">
                    <input type="checkbox" name="checkbox" id="checkbox-0">
                    <span></span>
                  </label>
                  <div>
                    <div class="date">01-01-2020</div>
                    <h3>Title of the post</h3>
                  </div>
                </div>
                <div class="option-mount">
                  <div class="visible">
                    <img src="<?=$GLOBALS["__HOME__"]?>/public/images/options-white.svg" alt="options" class="icon button-like">
                  </div>
                  
                  <div class="menu-body">
                    <div>
                      <span class="label">Choice 1</span>
                    </div>
                    <div>
                      <span class="label">Choice 2</span>
                      <span class="hint">Hint</span>
                    </div>
                    <div>
                      <span class="label">Choice 3</span>
                      <span class="hint">Hint</span>
                    </div>
                    <div>
                      <span class="label">Choice 4</span>
                    </div>
                    <div>
                      <span class="label">Choice 5</span>
                      <span class="hint">Hint</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <div class="user">
            <div class="u-head">
              <div class="start">
                <img src="<?=$GLOBALS["__HOME__"]?>/public/images/expand.svg" alt="expand" class="expand">
                <img src="<?=$GLOBALS["__HOME__"]?>/public/images/stock/pfp.png" alt="pfp">
                <div class="u-column">
                  <h4>Username</h4>
                  <span>website</span>
                </div>
              </div>
              <div class="end">
                <div class="option-mount">
                  <div class="visible">
                    <img src="<?=$GLOBALS["__HOME__"]?>/public/images/options-white.svg" alt="options" class="icon button-like">
                  </div>
                  
                  <div class="menu-body">
                    <div>
                      <span class="label">Choice 1</span>
                    </div>
                    <div>
                      <span class="label">Choice 2</span>
                      <span class="hint">Hint</span>
                    </div>
                    <div>
                      <span class="label">Choice 3</span>
                      <span class="hint">Hint</span>
                    </div>
                    <div>
                      <span class="label">Choice 4</span>
                    </div>
                    <div>
                      <span class="label">Choice 5</span>
                      <span class="hint">Hint</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="u-posts">
              <div class="post">
                <div class="absolute">
                  <img src="<?=$GLOBALS["__HOME__"]?>/public/images/theme-stock-pictures/__7612349654.png" alt="post img">
                  <div class="darken"></div>
                </div>
                <div class="content">
                  <label class="checkbox-container">
                    <input type="checkbox" name="checkbox" id="checkbox-0">
                    <span></span>
                  </label>
                  <div>
                    <div class="date">01-01-2020</div>
                    <h3>Title of the post</h3>
                  </div>
                </div>
                <div class="option-mount">
                  <div class="visible">
                    <img src="<?=$GLOBALS["__HOME__"]?>/public/images/options-white.svg" alt="options" class="icon button-like">
                  </div>
                  
                  <div class="menu-body">
                    <div>
                      <span class="label">Choice 1</span>
                    </div>
                    <div>
                      <span class="label">Choice 2</span>
                      <span class="hint">Hint</span>
                    </div>
                    <div>
                      <span class="label">Choice 3</span>
                      <span class="hint">Hint</span>
                    </div>
                    <div>
                      <span class="label">Choice 4</span>
                    </div>
                    <div>
                      <span class="label">Choice 5</span>
                      <span class="hint">Hint</span>
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="post">
                <div class="absolute">
                  <img src="<?=$GLOBALS["__HOME__"]?>/public/images/theme-stock-pictures/__89754345.png" alt="post img">
                  <div class="darken"></div>
                </div>
                <div class="content">
                  <label class="checkbox-container">
                    <input type="checkbox" name="checkbox" id="checkbox-0">
                    <span></span>
                  </label>
                  <div>
                    <div class="date">01-01-2020</div>
                    <h3>Title of the post</h3>
                  </div>
                </div>
                <div class="option-mount">
                  <div class="visible">
                    <img src="<?=$GLOBALS["__HOME__"]?>/public/images/options-white.svg" alt="options" class="icon button-like">
                  </div>
                  
                  <div class="menu-body">
                    <div>
                      <span class="label">Choice 1</span>
                    </div>
                    <div>
                      <span class="label">Choice 2</span>
                      <span class="hint">Hint</span>
                    </div>
                    <div>
                      <span class="label">Choice 3</span>
                      <span class="hint">Hint</span>
                    </div>
                    <div>
                      <span class="label">Choice 4</span>
                    </div>
                    <div>
                      <span class="label">Choice 5</span>
                      <span class="hint">Hint</span>
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="post">
                <div class="absolute">
                  <img src="<?=$GLOBALS["__HOME__"]?>/public/images/theme-stock-pictures/__80840328.png" alt="post img">
                  <div class="darken"></div>
                </div>
                <div class="content">
                  <label class="checkbox-container">
                    <input type="checkbox" name="checkbox" id="checkbox-0">
                    <span></span>
                  </label>
                  <div>
                    <div class="date">01-01-2020</div>
                    <h3>Title of the post</h3>
                  </div>
                </div>
                <div class="option-mount">
                  <div class="visible">
                    <img src="<?=$GLOBALS["__HOME__"]?>/public/images/options-white.svg" alt="options" class="icon button-like">
                  </div>
                  
                  <div class="menu-body">
                    <div>
                      <span class="label">Choice 1</span>
                    </div>
                    <div>
                      <span class="label">Choice 2</span>
                      <span class="hint">Hint</span>
                    </div>
                    <div>
                      <span class="label">Choice 3</span>
                      <span class="hint">Hint</span>
                    </div>
                    <div>
                      <span class="label">Choice 4</span>
                    </div>
                    <div>
                      <span class="label">Choice 5</span>
                      <span class="hint">Hint</span>
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="post">
                <div class="absolute">
                  <img src="<?=$GLOBALS["__HOME__"]?>/public/images/theme-stock-pictures/__8219034641.png" alt="post img">
                  <div class="darken"></div>
                </div>
                <div class="content">
                  <label class="checkbox-container">
                    <input type="checkbox" name="checkbox" id="checkbox-0">
                    <span></span>
                  </label>
                  <div>
                    <div class="date">01-01-2020</div>
                    <h3>Title of the post</h3>
                  </div>
                </div>
                <div class="option-mount">
                  <div class="visible">
                    <img src="<?=$GLOBALS["__HOME__"]?>/public/images/options-white.svg" alt="options" class="icon button-like">
                  </div>
                  
                  <div class="menu-body">
                    <div>
                      <span class="label">Choice 1</span>
                    </div>
                    <div>
                      <span class="label">Choice 2</span>
                      <span class="hint">Hint</span>
                    </div>
                    <div>
                      <span class="label">Choice 3</span>
                      <span class="hint">Hint</span>
                    </div>
                    <div>
                      <span class="label">Choice 4</span>
                    </div>
                    <div>
                      <span class="label">Choice 5</span>
                      <span class="hint">Hint</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <div class="user">
            <div class="u-head">
              <div class="start">
                <img src="<?=$GLOBALS["__HOME__"]?>/public/images/expand.svg" alt="expand" class="expand">
                <img src="<?=$GLOBALS["__HOME__"]?>/public/images/stock/pfp.png" alt="pfp">
                <div class="u-column">
                  <h4>Username</h4>
                  <span>website</span>
                </div>
              </div>
              <div class="end">
                <div class="option-mount">
                  <div class="visible">
                    <img src="<?=$GLOBALS["__HOME__"]?>/public/images/options-white.svg" alt="options" class="icon button-like">
                  </div>
                  
                  <div class="menu-body">
                    <div>
                      <span class="label">Choice 1</span>
                    </div>
                    <div>
                      <span class="label">Choice 2</span>
                      <span class="hint">Hint</span>
                    </div>
                    <div>
                      <span class="label">Choice 3</span>
                      <span class="hint">Hint</span>
                    </div>
                    <div>
                      <span class="label">Choice 4</span>
                    </div>
                    <div>
                      <span class="label">Choice 5</span>
                      <span class="hint">Hint</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="u-posts">
              <div class="post">
                <div class="absolute">
                  <img src="<?=$GLOBALS["__HOME__"]?>/public/images/theme-stock-pictures/__7612349654.png" alt="post img">
                  <div class="darken"></div>
                </div>
                <div class="content">
                  <label class="checkbox-container">
                    <input type="checkbox" name="checkbox" id="checkbox-0">
                    <span></span>
                  </label>
                  <div>
                    <div class="date">01-01-2020</div>
                    <h3>Title of the post</h3>
                  </div>
                </div>
                <div class="option-mount">
                  <div class="visible">
                    <img src="<?=$GLOBALS["__HOME__"]?>/public/images/options-white.svg" alt="options" class="icon button-like">
                  </div>
                  
                  <div class="menu-body">
                    <div>
                      <span class="label">Choice 1</span>
                    </div>
                    <div>
                      <span class="label">Choice 2</span>
                      <span class="hint">Hint</span>
                    </div>
                    <div>
                      <span class="label">Choice 3</span>
                      <span class="hint">Hint</span>
                    </div>
                    <div>
                      <span class="label">Choice 4</span>
                    </div>
                    <div>
                      <span class="label">Choice 5</span>
                      <span class="hint">Hint</span>
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="post">
                <div class="absolute">
                  <img src="<?=$GLOBALS["__HOME__"]?>/public/images/theme-stock-pictures/__89754345.png" alt="post img">
                  <div class="darken"></div>
                </div>
                <div class="content">
                  <label class="checkbox-container">
                    <input type="checkbox" name="checkbox" id="checkbox-0">
                    <span></span>
                  </label>
                  <div>
                    <div class="date">01-01-2020</div>
                    <h3>Title of the post</h3>
                  </div>
                </div>
                <div class="option-mount">
                  <div class="visible">
                    <img src="<?=$GLOBALS["__HOME__"]?>/public/images/options-white.svg" alt="options" class="icon button-like">
                  </div>
                  
                  <div class="menu-body">
                    <div>
                      <span class="label">Choice 1</span>
                    </div>
                    <div>
                      <span class="label">Choice 2</span>
                      <span class="hint">Hint</span>
                    </div>
                    <div>
                      <span class="label">Choice 3</span>
                      <span class="hint">Hint</span>
                    </div>
                    <div>
                      <span class="label">Choice 4</span>
                    </div>
                    <div>
                      <span class="label">Choice 5</span>
                      <span class="hint">Hint</span>
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="post">
                <div class="absolute">
                  <img src="<?=$GLOBALS["__HOME__"]?>/public/images/theme-stock-pictures/__80840328.png" alt="post img">
                  <div class="darken"></div>
                </div>
                <div class="content">
                  <label class="checkbox-container">
                    <input type="checkbox" name="checkbox" id="checkbox-0">
                    <span></span>
                  </label>
                  <div>
                    <div class="date">01-01-2020</div>
                    <h3>Title of the post</h3>
                  </div>
                </div>
                <div class="option-mount">
                  <div class="visible">
                    <img src="<?=$GLOBALS["__HOME__"]?>/public/images/options-white.svg" alt="options" class="icon button-like">
                  </div>
                  
                  <div class="menu-body">
                    <div>
                      <span class="label">Choice 1</span>
                    </div>
                    <div>
                      <span class="label">Choice 2</span>
                      <span class="hint">Hint</span>
                    </div>
                    <div>
                      <span class="label">Choice 3</span>
                      <span class="hint">Hint</span>
                    </div>
                    <div>
                      <span class="label">Choice 4</span>
                    </div>
                    <div>
                      <span class="label">Choice 5</span>
                      <span class="hint">Hint</span>
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="post">
                <div class="absolute">
                  <img src="<?=$GLOBALS["__HOME__"]?>/public/images/theme-stock-pictures/__8219034641.png" alt="post img">
                  <div class="darken"></div>
                </div>
                <div class="content">
                  <label class="checkbox-container">
                    <input type="checkbox" name="checkbox" id="checkbox-0">
                    <span></span>
                  </label>
                  <div>
                    <div class="date">01-01-2020</div>
                    <h3>Title of the post</h3>
                  </div>
                </div>
                <div class="option-mount">
                  <div class="visible">
                    <img src="<?=$GLOBALS["__HOME__"]?>/public/images/options-white.svg" alt="options" class="icon button-like">
                  </div>
                  
                  <div class="menu-body">
                    <div>
                      <span class="label">Choice 1</span>
                    </div>
                    <div>
                      <span class="label">Choice 2</span>
                      <span class="hint">Hint</span>
                    </div>
                    <div>
                      <span class="label">Choice 3</span>
                      <span class="hint">Hint</span>
                    </div>
                    <div>
                      <span class="label">Choice 4</span>
                    </div>
                    <div>
                      <span class="label">Choice 5</span>
                      <span class="hint">Hint</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <div class="user">
            <div class="u-head">
              <div class="start">
                <img src="<?=$GLOBALS["__HOME__"]?>/public/images/expand.svg" alt="expand" class="expand">
                <img src="<?=$GLOBALS["__HOME__"]?>/public/images/stock/pfp.png" alt="pfp">
                <div class="u-column">
                  <h4>Username</h4>
                  <span>website</span>
                </div>
              </div>
              <div class="end">
                <div class="option-mount">
                  <div class="visible">
                    <img src="<?=$GLOBALS["__HOME__"]?>/public/images/options-white.svg" alt="options" class="icon button-like">
                  </div>
                  
                  <div class="menu-body">
                    <div>
                      <span class="label">Choice 1</span>
                    </div>
                    <div>
                      <span class="label">Choice 2</span>
                      <span class="hint">Hint</span>
                    </div>
                    <div>
                      <span class="label">Choice 3</span>
                      <span class="hint">Hint</span>
                    </div>
                    <div>
                      <span class="label">Choice 4</span>
                    </div>
                    <div>
                      <span class="label">Choice 5</span>
                      <span class="hint">Hint</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="u-posts">
              <div class="post">
                <div class="absolute">
                  <img src="<?=$GLOBALS["__HOME__"]?>/public/images/theme-stock-pictures/__7612349654.png" alt="post img">
                  <div class="darken"></div>
                </div>
                <div class="content">
                  <label class="checkbox-container">
                    <input type="checkbox" name="checkbox" id="checkbox-0">
                    <span></span>
                  </label>
                  <div>
                    <div class="date">01-01-2020</div>
                    <h3>Title of the post</h3>
                  </div>
                </div>
                <div class="option-mount">
                  <div class="visible">
                    <img src="<?=$GLOBALS["__HOME__"]?>/public/images/options-white.svg" alt="options" class="icon button-like">
                  </div>
                  
                  <div class="menu-body">
                    <div>
                      <span class="label">Choice 1</span>
                    </div>
                    <div>
                      <span class="label">Choice 2</span>
                      <span class="hint">Hint</span>
                    </div>
                    <div>
                      <span class="label">Choice 3</span>
                      <span class="hint">Hint</span>
                    </div>
                    <div>
                      <span class="label">Choice 4</span>
                    </div>
                    <div>
                      <span class="label">Choice 5</span>
                      <span class="hint">Hint</span>
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="post">
                <div class="absolute">
                  <img src="<?=$GLOBALS["__HOME__"]?>/public/images/theme-stock-pictures/__89754345.png" alt="post img">
                  <div class="darken"></div>
                </div>
                <div class="content">
                  <label class="checkbox-container">
                    <input type="checkbox" name="checkbox" id="checkbox-0">
                    <span></span>
                  </label>
                  <div>
                    <div class="date">01-01-2020</div>
                    <h3>Title of the post</h3>
                  </div>
                </div>
                <div class="option-mount">
                  <div class="visible">
                    <img src="<?=$GLOBALS["__HOME__"]?>/public/images/options-white.svg" alt="options" class="icon button-like">
                  </div>
                  
                  <div class="menu-body">
                    <div>
                      <span class="label">Choice 1</span>
                    </div>
                    <div>
                      <span class="label">Choice 2</span>
                      <span class="hint">Hint</span>
                    </div>
                    <div>
                      <span class="label">Choice 3</span>
                      <span class="hint">Hint</span>
                    </div>
                    <div>
                      <span class="label">Choice 4</span>
                    </div>
                    <div>
                      <span class="label">Choice 5</span>
                      <span class="hint">Hint</span>
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="post">
                <div class="absolute">
                  <img src="<?=$GLOBALS["__HOME__"]?>/public/images/theme-stock-pictures/__80840328.png" alt="post img">
                  <div class="darken"></div>
                </div>
                <div class="content">
                  <label class="checkbox-container">
                    <input type="checkbox" name="checkbox" id="checkbox-0">
                    <span></span>
                  </label>
                  <div>
                    <div class="date">01-01-2020</div>
                    <h3>Title of the post</h3>
                  </div>
                </div>
                <div class="option-mount">
                  <div class="visible">
                    <img src="<?=$GLOBALS["__HOME__"]?>/public/images/options-white.svg" alt="options" class="icon button-like">
                  </div>
                  
                  <div class="menu-body">
                    <div>
                      <span class="label">Choice 1</span>
                    </div>
                    <div>
                      <span class="label">Choice 2</span>
                      <span class="hint">Hint</span>
                    </div>
                    <div>
                      <span class="label">Choice 3</span>
                      <span class="hint">Hint</span>
                    </div>
                    <div>
                      <span class="label">Choice 4</span>
                    </div>
                    <div>
                      <span class="label">Choice 5</span>
                      <span class="hint">Hint</span>
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="post">
                <div class="absolute">
                  <img src="<?=$GLOBALS["__HOME__"]?>/public/images/theme-stock-pictures/__8219034641.png" alt="post img">
                  <div class="darken"></div>
                </div>
                <div class="content">
                  <label class="checkbox-container">
                    <input type="checkbox" name="checkbox" id="checkbox-0">
                    <span></span>
                  </label>
                  <div>
                    <div class="date">01-01-2020</div>
                    <h3>Title of the post</h3>
                  </div>
                </div>
                <div class="option-mount">
                  <div class="visible">
                    <img src="<?=$GLOBALS["__HOME__"]?>/public/images/options-white.svg" alt="options" class="icon button-like">
                  </div>
                  
                  <div class="menu-body">
                    <div>
                      <span class="label">Choice 1</span>
                    </div>
                    <div>
                      <span class="label">Choice 2</span>
                      <span class="hint">Hint</span>
                    </div>
                    <div>
                      <span class="label">Choice 3</span>
                      <span class="hint">Hint</span>
                    </div>
                    <div>
                      <span class="label">Choice 4</span>
                    </div>
                    <div>
                      <span class="label">Choice 5</span>
                      <span class="hint">Hint</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <div class="user expanded">
            <div class="u-head">
              <div class="start">
                <img src="<?=$GLOBALS["__HOME__"]?>/public/images/expand.svg" alt="expand" class="expand">
                <img src="<?=$GLOBALS["__HOME__"]?>/public/images/stock/pfp.png" alt="pfp">
                <div class="u-column">
                  <h4>Username</h4>
                  <span>website</span>
                </div>
              </div>
              <div class="end">
                <div class="option-mount">
                  <div class="visible">
                    <img src="<?=$GLOBALS["__HOME__"]?>/public/images/options-white.svg" alt="options" class="icon button-like">
                  </div>
                  
                  <div class="menu-body">
                    <div>
                      <span class="label">Choice 1</span>
                    </div>
                    <div>
                      <span class="label">Choice 2</span>
                      <span class="hint">Hint</span>
                    </div>
                    <div>
                      <span class="label">Choice 3</span>
                      <span class="hint">Hint</span>
                    </div>
                    <div>
                      <span class="label">Choice 4</span>
                    </div>
                    <div>
                      <span class="label">Choice 5</span>
                      <span class="hint">Hint</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="u-posts">
              <div class="post">
                <div class="absolute">
                  <img src="<?=$GLOBALS["__HOME__"]?>/public/images/theme-stock-pictures/__7612349654.png" alt="post img">
                  <div class="darken"></div>
                </div>
                <div class="content">
                  <label class="checkbox-container">
                    <input type="checkbox" name="checkbox" id="checkbox-0">
                    <span></span>
                  </label>
                  <div>
                    <div class="date">01-01-2020</div>
                    <h3>Title of the post</h3>
                  </div>
                </div>
                <div class="option-mount">
                  <div class="visible">
                    <img src="<?=$GLOBALS["__HOME__"]?>/public/images/options-white.svg" alt="options" class="icon button-like">
                  </div>
                  
                  <div class="menu-body">
                    <div>
                      <span class="label">Choice 1</span>
                    </div>
                    <div>
                      <span class="label">Choice 2</span>
                      <span class="hint">Hint</span>
                    </div>
                    <div>
                      <span class="label">Choice 3</span>
                      <span class="hint">Hint</span>
                    </div>
                    <div>
                      <span class="label">Choice 4</span>
                    </div>
                    <div>
                      <span class="label">Choice 5</span>
                      <span class="hint">Hint</span>
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="post">
                <div class="absolute">
                  <img src="<?=$GLOBALS["__HOME__"]?>/public/images/theme-stock-pictures/__89754345.png" alt="post img">
                  <div class="darken"></div>
                </div>
                <div class="content">
                  <label class="checkbox-container">
                    <input type="checkbox" name="checkbox" id="checkbox-0">
                    <span></span>
                  </label>
                  <div>
                    <div class="date">01-01-2020</div>
                    <h3>Title of the post</h3>
                  </div>
                </div>
                <div class="option-mount">
                  <div class="visible">
                    <img src="<?=$GLOBALS["__HOME__"]?>/public/images/options-white.svg" alt="options" class="icon button-like">
                  </div>
                  
                  <div class="menu-body">
                    <div>
                      <span class="label">Choice 1</span>
                    </div>
                    <div>
                      <span class="label">Choice 2</span>
                      <span class="hint">Hint</span>
                    </div>
                    <div>
                      <span class="label">Choice 3</span>
                      <span class="hint">Hint</span>
                    </div>
                    <div>
                      <span class="label">Choice 4</span>
                    </div>
                    <div>
                      <span class="label">Choice 5</span>
                      <span class="hint">Hint</span>
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="post">
                <div class="absolute">
                  <img src="<?=$GLOBALS["__HOME__"]?>/public/images/theme-stock-pictures/__80840328.png" alt="post img">
                  <div class="darken"></div>
                </div>
                <div class="content">
                  <label class="checkbox-container">
                    <input type="checkbox" name="checkbox" id="checkbox-0">
                    <span></span>
                  </label>
                  <div>
                    <div class="date">01-01-2020</div>
                    <h3>Title of the post</h3>
                  </div>
                </div>
                <div class="option-mount">
                  <div class="visible">
                    <img src="<?=$GLOBALS["__HOME__"]?>/public/images/options-white.svg" alt="options" class="icon button-like">
                  </div>
                  
                  <div class="menu-body">
                    <div>
                      <span class="label">Choice 1</span>
                    </div>
                    <div>
                      <span class="label">Choice 2</span>
                      <span class="hint">Hint</span>
                    </div>
                    <div>
                      <span class="label">Choice 3</span>
                      <span class="hint">Hint</span>
                    </div>
                    <div>
                      <span class="label">Choice 4</span>
                    </div>
                    <div>
                      <span class="label">Choice 5</span>
                      <span class="hint">Hint</span>
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="post">
                <div class="absolute">
                  <img src="<?=$GLOBALS["__HOME__"]?>/public/images/theme-stock-pictures/__8219034641.png" alt="post img">
                  <div class="darken"></div>
                </div>
                <div class="content">
                  <label class="checkbox-container">
                    <input type="checkbox" name="checkbox" id="checkbox-0">
                    <span></span>
                  </label>
                  <div>
                    <div class="date">01-01-2020</div>
                    <h3>Title of the post</h3>
                  </div>
                </div>
                <div class="option-mount">
                  <div class="visible">
                    <img src="<?=$GLOBALS["__HOME__"]?>/public/images/options-white.svg" alt="options" class="icon button-like">
                  </div>
                  
                  <div class="menu-body">
                    <div>
                      <span class="label">Choice 1</span>
                    </div>
                    <div>
                      <span class="label">Choice 2</span>
                      <span class="hint">Hint</span>
                    </div>
                    <div>
                      <span class="label">Choice 3</span>
                      <span class="hint">Hint</span>
                    </div>
                    <div>
                      <span class="label">Choice 4</span>
                    </div>
                    <div>
                      <span class="label">Choice 5</span>
                      <span class="hint">Hint</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
    
    <div columns="4" class="c-grid link-element" auto-fill="true" id="appeals">
      <section column="0-4", row="0-4">
        <header class="no-mg">
          <section>
          </section>
        </header>
        
        <div class="scrollable appeals-view no-mg">
          <div class="appeal">
            <div class="a-head">
              <div class="u-head default">
                <div class="start">
                  <img src="<?=$GLOBALS["__HOME__"]?>/public/images/stock/pfp.png" alt="pfp">
                  <div class="u-column">
                    <h4>Username</h4>
                    <span>website</span>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="a-body">
              <div class="related-post">
                <h3>Title of the post</h3>
              </div>
              
              <div class="a-msg">
                Lorem ipsum dolor sit amet consectetur adipisicing elit. Suscipit optio laudantium, perspiciatis ducimus expedita repellat quidem nobis temporibus deserunt alias quae ipsam quia quaerat a accusantium inventore tempora atque culpa?
              </div>
              
              <div class="a-controls">
                <button>View post</button>
                <button>Accept</button>
                <button>Decline</button>
              </div>
            </div>
          </div>
          
          <div class="appeal">
            <div class="a-head">
              <div class="u-head default">
                <div class="start">
                  <img src="<?=$GLOBALS["__HOME__"]?>/public/images/stock/pfp.png" alt="pfp">
                  <div class="u-column">
                    <h4>Username</h4>
                    <span>website</span>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="a-body">
              <div class="related-post">
                <h3>Title of the post</h3>
              </div>
              
              <div class="a-msg">
                Lorem ipsum dolor sit amet consectetur adipisicing elit. Suscipit optio laudantium, perspiciatis ducimus expedita repellat quidem nobis temporibus deserunt alias quae ipsam quia quaerat a accusantium inventore tempora atque culpa?
              </div>
              
              <div class="a-controls">
                <button>View post</button>
                <button>Accept</button>
                <button>Decline</button>
              </div>
            </div>
          </div>
          
          <div class="appeal">
            <div class="a-head">
              <div class="u-head default">
                <div class="start">
                  <img src="<?=$GLOBALS["__HOME__"]?>/public/images/stock/pfp.png" alt="pfp">
                  <div class="u-column">
                    <h4>Username</h4>
                    <span>website</span>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="a-body">
              <div class="related-post">
                <h3>Title of the post</h3>
              </div>
              
              <div class="a-msg">
                Lorem ipsum dolor sit amet consectetur adipisicing elit. Suscipit optio laudantium, perspiciatis ducimus expedita repellat quidem nobis temporibus deserunt alias quae ipsam quia quaerat a accusantium inventore tempora atque culpa?
              </div>
              
              <div class="a-controls">
                <button>View post</button>
                <button>Accept</button>
                <button>Decline</button>
              </div>
            </div>
          </div>
          
          <div class="appeal">
            <div class="a-head">
              <div class="u-head default">
                <div class="start">
                  <img src="<?=$GLOBALS["__HOME__"]?>/public/images/stock/pfp.png" alt="pfp">
                  <div class="u-column">
                    <h4>Username</h4>
                    <span>website</span>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="a-body">
              <div class="related-post">
                <h3>Title of the post</h3>
              </div>
              
              <div class="a-msg">
                Lorem ipsum dolor sit amet consectetur adipisicing elit. Suscipit optio laudantium, perspiciatis ducimus expedita repellat quidem nobis temporibus deserunt alias quae ipsam quia quaerat a accusantium inventore tempora atque culpa?
              </div>
              
              <div class="a-controls">
                <button>View post</button>
                <button>Accept</button>
                <button>Decline</button>
              </div>
            </div>
          </div>
          
          <div class="appeal">
            <div class="a-head">
              <div class="u-head default">
                <div class="start">
                  <img src="<?=$GLOBALS["__HOME__"]?>/public/images/stock/pfp.png" alt="pfp">
                  <div class="u-column">
                    <h4>Username</h4>
                    <span>website</span>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="a-body">
              <div class="related-post">
                <h3>Title of the post</h3>
              </div>
              
              <div class="a-msg">
                Lorem ipsum dolor sit amet consectetur adipisicing elit. Suscipit optio laudantium, perspiciatis ducimus expedita repellat quidem nobis temporibus deserunt alias quae ipsam quia quaerat a accusantium inventore tempora atque culpa?
              </div>
              
              <div class="a-controls">
                <button>View post</button>
                <button>Accept</button>
                <button>Decline</button>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
  </main>
</body>
</html>