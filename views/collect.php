<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  
  <style>
      fieldset {
          display: flex;
          flex-direction: column;
      }
      button {
          width: fit-content;
      }
  </style>
</head>
<body>
  <form action="<?= $GLOBALS["__SERVER_HOME__"] ?>/file/collect" method="post" enctype="multipart/form-data">
    <fieldset>
      <legend>Images</legend>
      <button id="add-file">Add file</button>
    </fieldset>
    <button type="submit">Submit</button>
  </form>
  
  <script>
    document.querySelector("#add-file").addEventListener("click", evt => {
      const input = document.createElement("input");
      input.setAttribute("type", "file");
      input.setAttribute("name", "uploaded[]");
      input.setAttribute("multiple", "multiple");
      input.addEventListener("change", evt => {
        console.log(evt)
        console.log(evt.target.files)
      })
      
      document.querySelector("fieldset").insertBefore(input, evt.target);
      evt.preventDefault();
    });
  </script>
</body>
</html>