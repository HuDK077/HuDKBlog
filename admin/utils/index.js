/**
 * https://router.vuejs.org/en/advanced/scroll-behavior.html
 */
export function getMatchedComponents(route, matches = false, prop = 'components') {
  return Array.prototype.concat.apply([], route.matched.map((m, index) => {
    return Object.keys(m[prop]).map((key) => {
      matches && matches.push(index)
      return m[prop][key]
    })
  }))
}

export function interopDefault(promise) {
  return promise.then(m => m.default || m)
}

if (process.client) {
  if ('scrollRestoration' in window.history) {
    window.history.scrollRestoration = 'manual'

    // reset scrollRestoration to auto when leaving page, allowing page reload
    // and back-navigation from other pages to use the browser to restore the
    // scrolling position.
    window.addEventListener('beforeunload', () => {
      window.history.scrollRestoration = 'auto'
    })

    // Setting scrollRestoration to manual again when returning to this page.
    window.addEventListener('load', () => {
      window.history.scrollRestoration = 'manual'
    })
  }
}

export function scrollBehavior(to, from, savedPosition) {
  if (savedPosition) {
    return savedPosition
  } else {
    return { x: 0, y: 0 }
  }
  // // If the returned position is falsy or an empty object, will retain current scroll position
  // let position = false
  // const Pages = getMatchedComponents(to)
  // // Scroll to the top of the page if...
  // if (
  //   // One of the children set `scrollToTop`
  //   Pages.some(Page => Page.options.scrollToTop) ||
  //   // scrollToTop set in only page without children
  //   (Pages.length < 2 && Pages.every(Page => Page.options.scrollToTop !== false))
  // ) {
  //   position = { x: 0, y: 0 }
  // }
  // // savedPosition is only available for popstate navigations (back button)
  // if (savedPosition) {
  //   position = savedPosition
  // }
  // const nuxt = window.$nuxt
  // if (
  //   // Route hash changes
  //   (to.path === from.path && to.hash !== from.hash) ||
  //   // Initial load (vuejs/vue-router#3199)
  //   to === from
  // ) {
  //   nuxt.$nextTick(() => nuxt.$emit('triggerScroll'))
  // }
  // return new Promise((resolve) => {
  //   // wait for the out transition to complete (if necessary)
  //   nuxt.$once('triggerScroll', () => {
  //     // coords will be used if no selector is provided,
  //     // or if the selector didn't match any element.
  //     if (to.hash) {
  //       let hash = to.hash
  //       // CSS.escape() is not supported with IE and Edge.
  //       if (typeof window.CSS !== 'undefined' && typeof window.CSS.escape !== 'undefined') {
  //         hash = '#' + window.CSS.escape(hash.substr(1))
  //       }
  //       try {
  //         if (document.querySelector(hash)) {
  //           // scroll to anchor by returning the selector
  //           position = { selector: hash }
  //         }
  //       } catch (e) {
  //         console.warn('Failed to save scroll position. Please add CSS.escape() polyfill (https://github.com/mathiasbynens/CSS.escape).')
  //       }
  //     }
  //     resolve(position)
  //   })
  // })
}

/**
 * 深拷贝
 * @param {*} initalObj 源对象
 * @param {*} finalObj 目标对象
 */
export function deepClone(initalObj, finalObj) {
  if (initalObj.constructor === Array) {
    let arr = finalObj || [];
    arr = arr.concat(initalObj);
    return arr;
  } else {
    let obj = finalObj || {};
    for (let i in initalObj) {
      let prop = initalObj[i];
      if (prop === obj) {
        continue;
      }
      if (typeof prop === 'object') {
        obj[i] = (prop.constructor === Array) ? [] : Object.create(prop);
      } else {
        obj[i] = prop;
      }
    }
    return obj;
  }
}

/**
 * router 格式化
 * @param {Array} route 路由
 */
export function routerArr(route) {
  let arr = []
  route.forEach(v => {
    if (!v.meta.hidden) {
      let obj = { path: v.path, name: v.name, meta: v.meta };
      if (v.children) {
        let children = routerArr(v.children);
        if (children.length) {
          obj.children = children;
        } else {
          delete v.children
        }
      }
      arr.push(obj);
    }
  })
  return arr;
};
/**
 * 格式化日期
 * @param {String} reg 日期格式
 * @param {Number} data 时间戳/时间字符
 */
export function formatDate(reg, data) {
  if (!reg || !data) {
    return "";
  }
  if (isNaN(Number(data))) {
    data = Number(new Date(data.replace(/-/g, "/")));
  }

  if (data.toString().length === 10) {
    data *= 1000;
  }
  if (typeof data != "number") {
    data = Number(data);
  }
  let time = new Date(data);
  let o = {
    "M+": time.getMonth() + 1, // 月份
    "d+": time.getDate(), // 日
    "h+": time.getHours(), // 小时
    "m+": time.getMinutes(), // 分
    "s+": time.getSeconds(), // 秒
    "q+": Math.floor((time.getMonth() + 3) / 3), // 季度
    "S": time.getMilliseconds() // 毫秒
  };
  if (/(y+)/.test(reg)) {
    reg = reg.replace(RegExp.$1, (time.getFullYear() + "").substr(4 - RegExp.$1.length));
  }
  for (let k in o) {
    if (new RegExp("(" + k + ")").test(reg)) {
      reg = reg.replace(RegExp.$1, (RegExp.$1.length == 1) ? (o[k]) : (("00" + o[k]).substr(("" + o[k]).length)));
    }
  }
  return reg;
}

/**
 * 随机字符串
 * @param {Number} e 字符串长度
 */
export function randomString(e) {
  e = e || 32;
  var t = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890",
    a = t.length,
    n = "";
  for (i = 0; i < e; i++) n += t.charAt(Math.floor(Math.random() * a));
  return n
}

/**
 * 随机数
 * @param {Number} Min 最小值
 * @param {Number} Max 最大值
 */
export function randomNum(Min, Max) {
  var Range = Max - Min;
  var Rand = Math.random();
  return (Min + Math.round(Rand * Range));
}