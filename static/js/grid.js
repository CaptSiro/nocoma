const resizeObserver = new ResizeObserver((entries) => {
  for (let i = 0; i < entries.length; i++) {
    if ((entries[i].target.getAttribute("auto-fill") !== "true")) {
      const c = entries[i].target.getAttribute("columns") ?? 2;
      entries[i].target.style.gridAutoRows = ((entries[i].target.scrollWidth / ((c === "") ? 2 : +c)) - 25) + "px";
    }
  }
});

document.querySelectorAll(".c-grid").forEach(e => {
  const c = e.getAttribute("columns") ?? 2;
  const columns = (c === "") ? 2 : +c;

  e.style.gridTemplateColumns = `repeat(${columns}, 1fr)`;

  if (e.getAttribute("auto-fill") !== "true") {
    e.style.gridAutoRows = ((e.scrollWidth / columns) - 25) + "px";
  } else {
    e.style.gridTemplateRows = `repeat(${columns}, 1fr)`
  }

  resizeObserver.observe(e);

  for (const node of e.children) {
    let cs, ce, rs, re;
    const matchesC = /^([0-9]+)-([0-9]+)$/.exec(node.getAttribute("column"));

    if (matchesC === null) {
      cs = 1;
      ce = 2;
    } else {
      cs = (+matchesC[1]) + 1;
      ce = (+matchesC[2]) + cs;
    }

    const matchesR = /^([0-9]+)-([0-9]+)$/.exec(node.getAttribute("row"));
    if (matchesR === null) {
      rs = 1;
      ce = 2;
    } else {
      rs = (+matchesR[1]) + 1;
      re = (+matchesR[2]) + rs;
    }

    node.style.gridArea = rs + "/" + cs  + "/" + re + "/" + ce;
  }
});