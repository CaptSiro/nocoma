<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inspector</title>
  
  <script src="<?= $GLOBALS["__SERVER_HOME__"] ?>/public/js/main-v2.js"></script>
  <script>
    AJAX.DOMAIN_HOME = "<?=$GLOBALS["__HOME__"]?>";
    AJAX.SERVER_HOME = "<?=$GLOBALS["__SERVER_HOME__"] ?? $GLOBALS["__HOME__"]?>";
  </script>
  <script src="<?= $GLOBALS["__SERVER_HOME__"] ?>/public/js/widget-core.js"></script>
  
  <link rel="stylesheet" href="<?= $GLOBALS["__SERVER_HOME__"] ?>/public/css/main.css">
  
  <style>
      body {
        background-color: #0b0221;
        color: whitesmoke;
        padding: 16px;
      }
  </style>
</head>
<body>
  <script>
    const methods = {
      set() {},
      get() {}
    }
    
    document.body.append(
      CheckboxInspector(methods),
      CheckboxInspector(methods, "Hello"),
      
      TitleInspector("Hey i m a title"),
      
      RadioGroupInspector(methods, [{
        text: "Male",
        value: "male"
      }, {
        text: "Female",
        value: "female"
      }, {
        text: "Other",
        value: "other"
      }]),
      
      TextAreaInspector(methods),
      TextAreaInspector(methods, "My area"),
      TextAreaInspector(methods, "My area", "Message"),
      
      NumberInspector(methods, "Age", "18", "years"),
      NumberInspector(methods, "Width:", "20",
        SelectInspector(methods, [{
          text: "Pixels",
          value: "px"
        }, {
          text: "Inches",
          value: "in"
        }, {
          text: "Percentage",
          value: "%"
        }])
      ),
      
      DateInspector(methods, "Date of upload"),
      
      SelectInspector(methods, [{
        text: "Male",
        value: "male"
      }, {
        text: "Female",
        value: "female"
      }, {
        text: "Other",
        value: "other"
      }], "My select")
    );
  </script>
</body>
</html>