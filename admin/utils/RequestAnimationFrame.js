if (!window.requestAnimationFrame) {
  window.requestAnimationFrame = function () {
    return window.webkitRequestAnimationFrame ||
      window.mozRequestAnimationFrame ||
      window.oRequestAnimationFrame ||
      window.msRequestAnimationFrame ||
      function (callback, element) {
        console.log(">>>>");
        window.setTimeout(callback, 1e3 / 60);
      };
  }();
}
