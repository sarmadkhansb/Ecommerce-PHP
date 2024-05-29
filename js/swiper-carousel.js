(() => {
  "use strict";

  function e(e) {
    return null !== e && "object" == typeof e && "constructor" in e && e.constructor === Object
  }

  function t(s = {}, i = {}) {
    Object.keys(i).forEach((n => {
      void 0 === s[n] ? s[n] = i[n] : e(i[n]) && e(s[n]) && Object.keys(i[n]).length > 0 && t(s[n], i[n])
    }))
  }
  const s = {
    body: {},
    addEventListener() {},
    removeEventListener() {},
    activeElement: {
      blur() {},
      nodeName: ""
    },
    querySelector: () => null,
    querySelectorAll: () => [],
    getElementById: () => null,
    createEvent: () => ({
      initEvent() {}
    }),
    createElement: () => ({
      children: [],
      childNodes: [],
      style: {},
      setAttribute() {},
      getElementsByTagName: () => []
    }),
    createElementNS: () => ({}),
    importNode: () => null,
    location: {
      hash: "",
      host: "",
      hostname: "",
      href: "",
      origin: "",
      pathname: "",
      protocol: "",
      search: ""
    }
  };

  function i() {
    const e = "undefined" != typeof document ? document : {};
    return t(e, s), e
  }
  const n = {
    document: s,
    navigator: {
      userAgent: ""
    },
    location: {
      hash: "",
      host: "",
      hostname: "",
      href: "",
      origin: "",
      pathname: "",
      protocol: "",
      search: ""
    },
    history: {
      replaceState() {},
      pushState() {},
      go() {},
      back() {}
    },
    CustomEvent: function () {
      return this
    },
    addEventListener() {},
    removeEventListener() {},
    getComputedStyle: () => ({
      getPropertyValue: () => ""
    }),
    Image() {},
    Date() {},
    screen: {},
    setTimeout() {},
    clearTimeout() {},
    matchMedia: () => ({}),
    requestAnimationFrame: e => "undefined" == typeof setTimeout ? (e(), null) : setTimeout(e, 0),
    cancelAnimationFrame(e) {
      "undefined" != typeof setTimeout && clearTimeout(e)
    }
  };

  function r() {
    const e = "undefined" != typeof window ? window : {};
    return t(e, n), e
  }
  class a extends Array {
    constructor(e) {
      "number" == typeof e ? super(e) : (super(...e || []), function (e) {
        const t = e.__proto__;
        Object.defineProperty(e, "__proto__", {
          get: () => t,
          set(e) {
            t.__proto__ = e
          }
        })
      }(this))
    }
  }

  function l(e = []) {
    const t = [];
    return e.forEach((e => {
      Array.isArray(e) ? t.push(...l(e)) : t.push(e)
    })), t
  }

  function o(e, t) {
    return Array.prototype.filter.call(e, t)
  }

  function d(e, t) {
    const s = r(),
      n = i();
    let l = [];
    if (!t && e instanceof a) return e;
    if (!e) return new a(l);
    if ("string" == typeof e) {
      const s = e.trim();
      if (s.indexOf("<") >= 0 && s.indexOf(">") >= 0) {
        let e = "div";
        0 === s.indexOf("<li") && (e = "ul"), 0 === s.indexOf("<tr") && (e = "tbody"), (0 === s.indexOf("<td") || 0 === s.indexOf("<th")) && (e = "tr"), 0 === s.indexOf("<tbody") && (e = "table"), 0 === s.indexOf("<option") && (e = "select");
        const t = n.createElement(e);
        t.innerHTML = s;
        for (let e = 0; e < t.childNodes.length; e += 1) l.push(t.childNodes[e])
      } else l = function (e, t) {
        if ("string" != typeof e) return [e];
        const s = [],
          i = t.querySelectorAll(e);
        for (let e = 0; e < i.length; e += 1) s.push(i[e]);
        return s
      }(e.trim(), t || n)
    } else if (e.nodeType || e === s || e === n) l.push(e);
    else if (Array.isArray(e)) {
      if (e instanceof a) return e;
      l = e
    }
    return new a(function (e) {
      const t = [];
      for (let s = 0; s < e.length; s += 1) - 1 === t.indexOf(e[s]) && t.push(e[s]);
      return t
    }(l))
  }
  d.fn = a.prototype;
  const c = {
    addClass: function (...e) {
      const t = l(e.map((e => e.split(" "))));
      return this.forEach((e => {
        e.classList.add(...t)
      })), this
    },
    removeClass: function (...e) {
      const t = l(e.map((e => e.split(" "))));
      return this.forEach((e => {
        e.classList.remove(...t)
      })), this
    },
    hasClass: function (...e) {
      const t = l(e.map((e => e.split(" "))));
      return o(this, (e => t.filter((t => e.classList.contains(t))).length > 0)).length > 0
    },
    toggleClass: function (...e) {
      const t = l(e.map((e => e.split(" "))));
      this.forEach((e => {
        t.forEach((t => {
          e.classList.toggle(t)
        }))
      }))
    },
    attr: function (e, t) {
      if (1 === arguments.length && "string" == typeof e) return this[0] ? this[0].getAttribute(e) : void 0;
      for (let s = 0; s < this.length; s += 1)
        if (2 === arguments.length) this[s].setAttribute(e, t);
        else
          for (const t in e) this[s][t] = e[t], this[s].setAttribute(t, e[t]);
      return this
    },
    removeAttr: function (e) {
      for (let t = 0; t < this.length; t += 1) this[t].removeAttribute(e);
      return this
    },
    transform: function (e) {
      for (let t = 0; t < this.length; t += 1) this[t].style.transform = e;
      return this
    },
    transition: function (e) {
      for (let t = 0; t < this.length; t += 1) this[t].style.transitionDuration = "string" != typeof e ? `${e}ms` : e;
      return this
    },
    on: function (...e) {
      let [t, s, i, n] = e;

      function r(e) {
        const t = e.target;
        if (!t) return;
        const n = e.target.dom7EventData || [];
        if (n.indexOf(e) < 0 && n.unshift(e), d(t).is(s)) i.apply(t, n);
        else {
          const e = d(t).parents();
          for (let t = 0; t < e.length; t += 1) d(e[t]).is(s) && i.apply(e[t], n)
        }
      }

      function a(e) {
        const t = e && e.target && e.target.dom7EventData || [];
        t.indexOf(e) < 0 && t.unshift(e), i.apply(this, t)
      }
      "function" == typeof e[1] && ([t, i, n] = e, s = void 0), n || (n = !1);
      const l = t.split(" ");
      let o;
      for (let e = 0; e < this.length; e += 1) {
        const t = this[e];
        if (s)
          for (o = 0; o < l.length; o += 1) {
            const e = l[o];
            t.dom7LiveListeners || (t.dom7LiveListeners = {}), t.dom7LiveListeners[e] || (t.dom7LiveListeners[e] = []), t.dom7LiveListeners[e].push({
              listener: i,
              proxyListener: r
            }), t.addEventListener(e, r, n)
          } else
            for (o = 0; o < l.length; o += 1) {
              const e = l[o];
              t.dom7Listeners || (t.dom7Listeners = {}), t.dom7Listeners[e] || (t.dom7Listeners[e] = []), t.dom7Listeners[e].push({
                listener: i,
                proxyListener: a
              }), t.addEventListener(e, a, n)
            }
      }
      return this
    },
    off: function (...e) {
      let [t, s, i, n] = e;
      "function" == typeof e[1] && ([t, i, n] = e, s = void 0), n || (n = !1);
      const r = t.split(" ");
      for (let e = 0; e < r.length; e += 1) {
        const t = r[e];
        for (let e = 0; e < this.length; e += 1) {
          const r = this[e];
          let a;
          if (!s && r.dom7Listeners ? a = r.dom7Listeners[t] : s && r.dom7LiveListeners && (a = r.dom7LiveListeners[t]), a && a.length)
            for (let e = a.length - 1; e >= 0; e -= 1) {
              const s = a[e];
              i && s.listener === i || i && s.listener && s.listener.dom7proxy && s.listener.dom7proxy === i ? (r.removeEventListener(t, s.proxyListener, n), a.splice(e, 1)) : i || (r.removeEventListener(t, s.proxyListener, n), a.splice(e, 1))
            }
        }
      }
      return this
    },
    trigger: function (...e) {
      const t = r(),
        s = e[0].split(" "),
        i = e[1];
      for (let n = 0; n < s.length; n += 1) {
        const r = s[n];
        for (let s = 0; s < this.length; s += 1) {
          const n = this[s];
          if (t.CustomEvent) {
            const s = new t.CustomEvent(r, {
              detail: i,
              bubbles: !0,
              cancelable: !0
            });
            n.dom7EventData = e.filter(((e, t) => t > 0)), n.dispatchEvent(s), n.dom7EventData = [], delete n.dom7EventData
          }
        }
      }
      return this
    },
    transitionEnd: function (e) {
      const t = this;
      return e && t.on("transitionend", (function s(i) {
        i.target === this && (e.call(this, i), t.off("transitionend", s))
      })), this
    },
    outerWidth: function (e) {
      if (this.length > 0) {
        if (e) {
          const e = this.styles();
          return this[0].offsetWidth + parseFloat(e.getPropertyValue("margin-right")) + parseFloat(e.getPropertyValue("margin-left"))
        }
        return this[0].offsetWidth
      }
      return null
    },
    outerHeight: function (e) {
      if (this.length > 0) {
        if (e) {
          const e = this.styles();
          return this[0].offsetHeight + parseFloat(e.getPropertyValue("margin-top")) + parseFloat(e.getPropertyValue("margin-bottom"))
        }
        return this[0].offsetHeight
      }
      return null
    },
    styles: function () {
      const e = r();
      return this[0] ? e.getComputedStyle(this[0], null) : {}
    },
    offset: function () {
      if (this.length > 0) {
        const e = r(),
          t = i(),
          s = this[0],
          n = s.getBoundingClientRect(),
          a = t.body,
          l = s.clientTop || a.clientTop || 0,
          o = s.clientLeft || a.clientLeft || 0,
          d = s === e ? e.scrollY : s.scrollTop,
          c = s === e ? e.scrollX : s.scrollLeft;
        return {
          top: n.top + d - l,
          left: n.left + c - o
        }
      }
      return null
    },
    css: function (e, t) {
      const s = r();
      let i;
      if (1 === arguments.length) {
        if ("string" != typeof e) {
          for (i = 0; i < this.length; i += 1)
            for (const t in e) this[i].style[t] = e[t];
          return this
        }
        if (this[0]) return s.getComputedStyle(this[0], null).getPropertyValue(e)
      }
      if (2 === arguments.length && "string" == typeof e) {
        for (i = 0; i < this.length; i += 1) this[i].style[e] = t;
        return this
      }
      return this
    },
    each: function (e) {
      return e ? (this.forEach(((t, s) => {
        e.apply(t, [t, s])
      })), this) : this
    },
    html: function (e) {
      if (void 0 === e) return this[0] ? this[0].innerHTML : null;
      for (let t = 0; t < this.length; t += 1) this[t].innerHTML = e;
      return this
    },
    text: function (e) {
      if (void 0 === e) return this[0] ? this[0].textContent.trim() : null;
      for (let t = 0; t < this.length; t += 1) this[t].textContent = e;
      return this
    },
    is: function (e) {
      const t = r(),
        s = i(),
        n = this[0];
      let l, o;
      if (!n || void 0 === e) return !1;
      if ("string" == typeof e) {
        if (n.matches) return n.matches(e);
        if (n.webkitMatchesSelector) return n.webkitMatchesSelector(e);
        if (n.msMatchesSelector) return n.msMatchesSelector(e);
        for (l = d(e), o = 0; o < l.length; o += 1)
          if (l[o] === n) return !0;
        return !1
      }
      if (e === s) return n === s;
      if (e === t) return n === t;
      if (e.nodeType || e instanceof a) {
        for (l = e.nodeType ? [e] : e, o = 0; o < l.length; o += 1)
          if (l[o] === n) return !0;
        return !1
      }
      return !1
    },
    index: function () {
      let e, t = this[0];
      if (t) {
        for (e = 0; null !== (t = t.previousSibling);) 1 === t.nodeType && (e += 1);
        return e
      }
    },
    eq: function (e) {
      if (void 0 === e) return this;
      const t = this.length;
      if (e > t - 1) return d([]);
      if (e < 0) {
        const s = t + e;
        return d(s < 0 ? [] : [this[s]])
      }
      return d([this[e]])
    },
    append: function (...e) {
      let t;
      const s = i();
      for (let i = 0; i < e.length; i += 1) {
        t = e[i];
        for (let e = 0; e < this.length; e += 1)
          if ("string" == typeof t) {
            const i = s.createElement("div");
            for (i.innerHTML = t; i.firstChild;) this[e].appendChild(i.firstChild)
          } else if (t instanceof a)
          for (let s = 0; s < t.length; s += 1) this[e].appendChild(t[s]);
        else this[e].appendChild(t)
      }
      return this
    },
    prepend: function (e) {
      const t = i();
      let s, n;
      for (s = 0; s < this.length; s += 1)
        if ("string" == typeof e) {
          const i = t.createElement("div");
          for (i.innerHTML = e, n = i.childNodes.length - 1; n >= 0; n -= 1) this[s].insertBefore(i.childNodes[n], this[s].childNodes[0])
        } else if (e instanceof a)
        for (n = 0; n < e.length; n += 1) this[s].insertBefore(e[n], this[s].childNodes[0]);
      else this[s].insertBefore(e, this[s].childNodes[0]);
      return this
    },
    next: function (e) {
      return this.length > 0 ? e ? this[0].nextElementSibling && d(this[0].nextElementSibling).is(e) ? d([this[0].nextElementSibling]) : d([]) : this[0].nextElementSibling ? d([this[0].nextElementSibling]) : d([]) : d([])
    },
    nextAll: function (e) {
      const t = [];
      let s = this[0];
      if (!s) return d([]);
      for (; s.nextElementSibling;) {
        const i = s.nextElementSibling;
        e ? d(i).is(e) && t.push(i) : t.push(i), s = i
      }
      return d(t)
    },
    prev: function (e) {
      if (this.length > 0) {
        const t = this[0];
        return e ? t.previousElementSibling && d(t.previousElementSibling).is(e) ? d([t.previousElementSibling]) : d([]) : t.previousElementSibling ? d([t.previousElementSibling]) : d([])
      }
      return d([])
    },
    prevAll: function (e) {
      const t = [];
      let s = this[0];
      if (!s) return d([]);
      for (; s.previousElementSibling;) {
        const i = s.previousElementSibling;
        e ? d(i).is(e) && t.push(i) : t.push(i), s = i
      }
      return d(t)
    },
    parent: function (e) {
      const t = [];
      for (let s = 0; s < this.length; s += 1) null !== this[s].parentNode && (e ? d(this[s].parentNode).is(e) && t.push(this[s].parentNode) : t.push(this[s].parentNode));
      return d(t)
    },
    parents: function (e) {
      const t = [];
      for (let s = 0; s < this.length; s += 1) {
        let i = this[s].parentNode;
        for (; i;) e ? d(i).is(e) && t.push(i) : t.push(i), i = i.parentNode
      }
      return d(t)
    },
    closest: function (e) {
      let t = this;
      return void 0 === e ? d([]) : (t.is(e) || (t = t.parents(e).eq(0)), t)
    },
    find: function (e) {
      const t = [];
      for (let s = 0; s < this.length; s += 1) {
        const i = this[s].querySelectorAll(e);
        for (let e = 0; e < i.length; e += 1) t.push(i[e])
      }
      return d(t)
    },
    children: function (e) {
      const t = [];
      for (let s = 0; s < this.length; s += 1) {
        const i = this[s].children;
        for (let s = 0; s < i.length; s += 1)(!e || d(i[s]).is(e)) && t.push(i[s])
      }
      return d(t)
    },
    filter: function (e) {
      return d(o(this, e))
    },
    remove: function () {
      for (let e = 0; e < this.length; e += 1) this[e].parentNode && this[e].parentNode.removeChild(this[e]);
      return this
    }
  };

  function p(e, t) {
    return void 0 === t && (t = 0), setTimeout(e, t)
  }

  function u() {
    return Date.now()
  }

  function h(e) {
    return "object" == typeof e && null !== e && e.constructor && "Object" === Object.prototype.toString.call(e).slice(8, -1)
  }

  function f(e) {
    return "undefined" != typeof window && void 0 !== window.HTMLElement ? e instanceof HTMLElement : e && (1 === e.nodeType || 11 === e.nodeType)
  }

  function m() {
    const e = Object(arguments.length <= 0 ? void 0 : arguments[0]),
      t = ["__proto__", "constructor", "prototype"];
    for (let s = 1; s < arguments.length; s += 1) {
      const i = s < 0 || arguments.length <= s ? void 0 : arguments[s];
      if (null != i && !f(i)) {
        const s = Object.keys(Object(i)).filter((e => t.indexOf(e) < 0));
        for (let t = 0, n = s.length; t < n; t += 1) {
          const n = s[t],
            r = Object.getOwnPropertyDescriptor(i, n);
          void 0 !== r && r.enumerable && (h(e[n]) && h(i[n]) ? i[n].__swiper__ ? e[n] = i[n] : m(e[n], i[n]) : !h(e[n]) && h(i[n]) ? (e[n] = {}, i[n].__swiper__ ? e[n] = i[n] : m(e[n], i[n])) : e[n] = i[n])
        }
      }
    }
    return e
  }

  function g(e, t, s) {
    e.style.setProperty(t, s)
  }

  function v(e) {
    let {
      swiper: t,
      targetPosition: s,
      side: i
    } = e;
    const n = r(),
      a = -t.translate;
    let l, o = null;
    const d = t.params.speed;
    t.wrapperEl.style.scrollSnapType = "none", n.cancelAnimationFrame(t.cssModeFrameID);
    const c = s > a ? "next" : "prev",
      p = (e, t) => "next" === c && e >= t || "prev" === c && e <= t,
      u = () => {
        l = (new Date).getTime(), null === o && (o = l);
        const e = Math.max(Math.min((l - o) / d, 1), 0),
          r = .5 - Math.cos(e * Math.PI) / 2;
        let c = a + r * (s - a);
        if (p(c, s) && (c = s), t.wrapperEl.scrollTo({
            [i]: c
          }), p(c, s)) return t.wrapperEl.style.overflow = "hidden", t.wrapperEl.style.scrollSnapType = "", setTimeout((() => {
          t.wrapperEl.style.overflow = "", t.wrapperEl.scrollTo({
            [i]: c
          })
        })), void n.cancelAnimationFrame(t.cssModeFrameID);
        t.cssModeFrameID = n.requestAnimationFrame(u)
      };
    u()
  }
  let w, b, C;

  function T() {
    return w || (w = function () {
      const e = r(),
        t = i();
      return {
        smoothScroll: t.documentElement && "scrollBehavior" in t.documentElement.style,
        touch: !!("ontouchstart" in e || e.DocumentTouch && t instanceof e.DocumentTouch),
        passiveListener: function () {
          let t = !1;
          try {
            const s = Object.defineProperty({}, "passive", {
              get() {
                t = !0
              }
            });
            e.addEventListener("testPassiveListener", null, s)
          } catch {}
          return t
        }(),
        gestures: "ongesturestart" in e
      }
    }()), w
  }
  Object.keys(c).forEach((e => {
    Object.defineProperty(d.fn, e, {
      value: c[e],
      writable: !0
    })
  }));
  var S = {
      on(e, t, s) {
        const i = this;
        if ("function" != typeof t) return i;
        const n = s ? "unshift" : "push";
        return e.split(" ").forEach((e => {
          i.eventsListeners[e] || (i.eventsListeners[e] = []), i.eventsListeners[e][n](t)
        })), i
      },
      once(e, t, s) {
        const i = this;
        if ("function" != typeof t) return i;

        function n() {
          i.off(e, n), n.__emitterProxy && delete n.__emitterProxy;
          for (var s = arguments.length, r = new Array(s), a = 0; a < s; a++) r[a] = arguments[a];
          t.apply(i, r)
        }
        return n.__emitterProxy = t, i.on(e, n, s)
      },
      onAny(e, t) {
        const s = this;
        if ("function" != typeof e) return s;
        const i = t ? "unshift" : "push";
        return s.eventsAnyListeners.indexOf(e) < 0 && s.eventsAnyListeners[i](e), s
      },
      offAny(e) {
        const t = this;
        if (!t.eventsAnyListeners) return t;
        const s = t.eventsAnyListeners.indexOf(e);
        return s >= 0 && t.eventsAnyListeners.splice(s, 1), t
      },
      off(e, t) {
        const s = this;
        return s.eventsListeners && e.split(" ").forEach((e => {
          void 0 === t ? s.eventsListeners[e] = [] : s.eventsListeners[e] && s.eventsListeners[e].forEach(((i, n) => {
            (i === t || i.__emitterProxy && i.__emitterProxy === t) && s.eventsListeners[e].splice(n, 1)
          }))
        })), s
      },
      emit() {
        const e = this;
        if (!e.eventsListeners) return e;
        let t, s, i;
        for (var n = arguments.length, r = new Array(n), a = 0; a < n; a++) r[a] = arguments[a];
        return "string" == typeof r[0] || Array.isArray(r[0]) ? (t = r[0], s = r.slice(1, r.length), i = e) : (t = r[0].events, s = r[0].data, i = r[0].context || e), s.unshift(i), (Array.isArray(t) ? t : t.split(" ")).forEach((t => {
          e.eventsAnyListeners && e.eventsAnyListeners.length && e.eventsAnyListeners.forEach((e => {
            e.apply(i, [t, ...s])
          })), e.eventsListeners && e.eventsListeners[t] && e.eventsListeners[t].forEach((e => {
            e.apply(i, s)
          }))
        })), e
      }
    },
    y = {
      updateSize: function () {
        const e = this;
        let t, s;
        const i = e.$el;
        t = void 0 !== e.params.width && null !== e.params.width ? e.params.width : i[0].clientWidth, s = void 0 !== e.params.height && null !== e.params.height ? e.params.height : i[0].clientHeight, !(0 === t && e.isHorizontal() || 0 === s && e.isVertical()) && (t = t - parseInt(i.css("padding-left") || 0, 10) - parseInt(i.css("padding-right") || 0, 10), s = s - parseInt(i.css("padding-top") || 0, 10) - parseInt(i.css("padding-bottom") || 0, 10), Number.isNaN(t) && (t = 0), Number.isNaN(s) && (s = 0), Object.assign(e, {
          width: t,
          height: s,
          size: e.isHorizontal() ? t : s
        }))
      },
      updateSlides: function () {
        const e = this;

        function t(t) {
          return e.isHorizontal() ? t : {
            width: "height",
            "margin-top": "margin-left",
            "margin-bottom ": "margin-right",
            "margin-left": "margin-top",
            "margin-right": "margin-bottom",
            "padding-left": "padding-top",
            "padding-right": "padding-bottom",
            marginRight: "marginBottom"
          } [t]
        }

        function s(e, s) {
          return parseFloat(e.getPropertyValue(t(s)) || 0)
        }
        const i = e.params,
          {
            $wrapperEl: n,
            size: r,
            rtlTranslate: a,
            wrongRTL: l
          } = e,
          o = e.virtual && i.virtual.enabled,
          d = o ? e.virtual.slides.length : e.slides.length,
          c = n.children(`.${e.params.slideClass}`),
          p = o ? e.virtual.slides.length : c.length;
        let u = [];
        const h = [],
          f = [];
        let m = i.slidesOffsetBefore;
        "function" == typeof m && (m = i.slidesOffsetBefore.call(e));
        let v = i.slidesOffsetAfter;
        "function" == typeof v && (v = i.slidesOffsetAfter.call(e));
        const w = e.snapGrid.length,
          b = e.slidesGrid.length;
        let C = i.spaceBetween,
          T = -m,
          S = 0,
          y = 0;
        if (void 0 === r) return;
        "string" == typeof C && C.indexOf("%") >= 0 && (C = parseFloat(C.replace("%", "")) / 100 * r), e.virtualSize = -C, a ? c.css({
          marginLeft: "",
          marginBottom: "",
          marginTop: ""
        }) : c.css({
          marginRight: "",
          marginBottom: "",
          marginTop: ""
        }), i.centeredSlides && i.cssMode && (g(e.wrapperEl, "--swiper-centered-offset-before", ""), g(e.wrapperEl, "--swiper-centered-offset-after", ""));
        const E = i.grid && i.grid.rows > 1 && e.grid;
        let x;
        E && e.grid.initSlides(p);
        const M = "auto" === i.slidesPerView && i.breakpoints && Object.keys(i.breakpoints).filter((e => void 0 !== i.breakpoints[e].slidesPerView)).length > 0;
        for (let n = 0; n < p; n += 1) {
          x = 0;
          const a = c.eq(n);
          if (E && e.grid.updateSlide(n, a, p, t), "none" !== a.css("display")) {
            if ("auto" === i.slidesPerView) {
              M && (c[n].style[t("width")] = "");
              const r = getComputedStyle(a[0]),
                l = a[0].style.transform,
                o = a[0].style.webkitTransform;
              if (l && (a[0].style.transform = "none"), o && (a[0].style.webkitTransform = "none"), i.roundLengths) x = e.isHorizontal() ? a.outerWidth(!0) : a.outerHeight(!0);
              else {
                const e = s(r, "width"),
                  t = s(r, "padding-left"),
                  i = s(r, "padding-right"),
                  n = s(r, "margin-left"),
                  l = s(r, "margin-right"),
                  o = r.getPropertyValue("box-sizing");
                if (o && "border-box" === o) x = e + n + l;
                else {
                  const {
                    clientWidth: s,
                    offsetWidth: r
                  } = a[0];
                  x = e + t + i + n + l + (r - s)
                }
              }
              l && (a[0].style.transform = l), o && (a[0].style.webkitTransform = o), i.roundLengths && (x = Math.floor(x))
            } else x = (r - (i.slidesPerView - 1) * C) / i.slidesPerView, i.roundLengths && (x = Math.floor(x)), c[n] && (c[n].style[t("width")] = `${x}px`);
            c[n] && (c[n].swiperSlideSize = x), f.push(x), i.centeredSlides ? (T = T + x / 2 + S / 2 + C, 0 === S && 0 !== n && (T = T - r / 2 - C), 0 === n && (T = T - r / 2 - C), Math.abs(T) < .001 && (T = 0), i.roundLengths && (T = Math.floor(T)), y % i.slidesPerGroup == 0 && u.push(T), h.push(T)) : (i.roundLengths && (T = Math.floor(T)), (y - Math.min(e.params.slidesPerGroupSkip, y)) % e.params.slidesPerGroup == 0 && u.push(T), h.push(T), T = T + x + C), e.virtualSize += x + C, S = x, y += 1
          }
        }
        if (e.virtualSize = Math.max(e.virtualSize, r) + v, a && l && ("slide" === i.effect || "coverflow" === i.effect) && n.css({
            width: `${e.virtualSize+i.spaceBetween}px`
          }), i.setWrapperSize && n.css({
            [t("width")]: `${e.virtualSize+i.spaceBetween}px`
          }), E && e.grid.updateWrapperSize(x, u, t), !i.centeredSlides) {
          const t = [];
          for (let s = 0; s < u.length; s += 1) {
            let n = u[s];
            i.roundLengths && (n = Math.floor(n)), u[s] <= e.virtualSize - r && t.push(n)
          }
          u = t, Math.floor(e.virtualSize - r) - Math.floor(u[u.length - 1]) > 1 && u.push(e.virtualSize - r)
        }
        if (0 === u.length && (u = [0]), 0 !== i.spaceBetween) {
          const s = e.isHorizontal() && a ? "marginLeft" : t("marginRight");
          c.filter(((e, t) => !i.cssMode || t !== c.length - 1)).css({
            [s]: `${C}px`
          })
        }
        if (i.centeredSlides && i.centeredSlidesBounds) {
          let e = 0;
          f.forEach((t => {
            e += t + (i.spaceBetween ? i.spaceBetween : 0)
          })), e -= i.spaceBetween;
          const t = e - r;
          u = u.map((e => e < 0 ? -m : e > t ? t + v : e))
        }
        if (i.centerInsufficientSlides) {
          let e = 0;
          if (f.forEach((t => {
              e += t + (i.spaceBetween ? i.spaceBetween : 0)
            })), e -= i.spaceBetween, e < r) {
            const t = (r - e) / 2;
            u.forEach(((e, s) => {
              u[s] = e - t
            })), h.forEach(((e, s) => {
              h[s] = e + t
            }))
          }
        }
        if (Object.assign(e, {
            slides: c,
            snapGrid: u,
            slidesGrid: h,
            slidesSizesGrid: f
          }), i.centeredSlides && i.cssMode && !i.centeredSlidesBounds) {
          g(e.wrapperEl, "--swiper-centered-offset-before", -u[0] + "px"), g(e.wrapperEl, "--swiper-centered-offset-after", e.size / 2 - f[f.length - 1] / 2 + "px");
          const t = -e.snapGrid[0],
            s = -e.slidesGrid[0];
          e.snapGrid = e.snapGrid.map((e => e + t)), e.slidesGrid = e.slidesGrid.map((e => e + s))
        }
        if (p !== d && e.emit("slidesLengthChange"), u.length !== w && (e.params.watchOverflow && e.checkOverflow(), e.emit("snapGridLengthChange")), h.length !== b && e.emit("slidesGridLengthChange"), i.watchSlidesProgress && e.updateSlidesOffset(), !(o || i.cssMode || "slide" !== i.effect && "fade" !== i.effect)) {
          const t = `${i.containerModifierClass}backface-hidden`,
            s = e.$el.hasClass(t);
          p <= i.maxBackfaceHiddenSlides ? s || e.$el.addClass(t) : s && e.$el.removeClass(t)
        }
      },
      updateAutoHeight: function (e) {
        const t = this,
          s = [],
          i = t.virtual && t.params.virtual.enabled;
        let n, r = 0;
        "number" == typeof e ? t.setTransition(e) : !0 === e && t.setTransition(t.params.speed);
        const a = e => i ? t.slides.filter((t => parseInt(t.getAttribute("data-swiper-slide-index"), 10) === e))[0] : t.slides.eq(e)[0];
        if ("auto" !== t.params.slidesPerView && t.params.slidesPerView > 1)
          if (t.params.centeredSlides) t.visibleSlides.each((e => {
            s.push(e)
          }));
          else
            for (n = 0; n < Math.ceil(t.params.slidesPerView); n += 1) {
              const e = t.activeIndex + n;
              if (e > t.slides.length && !i) break;
              s.push(a(e))
            } else s.push(a(t.activeIndex));
        for (n = 0; n < s.length; n += 1)
          if (void 0 !== s[n]) {
            const e = s[n].offsetHeight;
            r = e > r ? e : r
          }(r || 0 === r) && t.$wrapperEl.css("height", `${r}px`)
      },
      updateSlidesOffset: function () {
        const e = this,
          t = e.slides;
        for (let s = 0; s < t.length; s += 1) t[s].swiperSlideOffset = e.isHorizontal() ? t[s].offsetLeft : t[s].offsetTop
      },
      updateSlidesProgress: function (e) {
        void 0 === e && (e = this && this.translate || 0);
        const t = this,
          s = t.params,
          {
            slides: i,
            rtlTranslate: n,
            snapGrid: r
          } = t;
        if (0 === i.length) return;
        void 0 === i[0].swiperSlideOffset && t.updateSlidesOffset();
        let a = -e;
        n && (a = e), i.removeClass(s.slideVisibleClass), t.visibleSlidesIndexes = [], t.visibleSlides = [];
        for (let e = 0; e < i.length; e += 1) {
          const l = i[e];
          let o = l.swiperSlideOffset;
          s.cssMode && s.centeredSlides && (o -= i[0].swiperSlideOffset);
          const d = (a + (s.centeredSlides ? t.minTranslate() : 0) - o) / (l.swiperSlideSize + s.spaceBetween),
            c = (a - r[0] + (s.centeredSlides ? t.minTranslate() : 0) - o) / (l.swiperSlideSize + s.spaceBetween),
            p = -(a - o),
            u = p + t.slidesSizesGrid[e];
          (p >= 0 && p < t.size - 1 || u > 1 && u <= t.size || p <= 0 && u >= t.size) && (t.visibleSlides.push(l), t.visibleSlidesIndexes.push(e), i.eq(e).addClass(s.slideVisibleClass)), l.progress = n ? -d : d, l.originalProgress = n ? -c : c
        }
        t.visibleSlides = d(t.visibleSlides)
      },
      updateProgress: function (e) {
        const t = this;
        if (void 0 === e) {
          const s = t.rtlTranslate ? -1 : 1;
          e = t && t.translate && t.translate * s || 0
        }
        const s = t.params,
          i = t.maxTranslate() - t.minTranslate();
        let {
          progress: n,
          isBeginning: r,
          isEnd: a
        } = t;
        const l = r,
          o = a;
        0 === i ? (n = 0, r = !0, a = !0) : (n = (e - t.minTranslate()) / i, r = n <= 0, a = n >= 1), Object.assign(t, {
          progress: n,
          isBeginning: r,
          isEnd: a
        }), (s.watchSlidesProgress || s.centeredSlides && s.autoHeight) && t.updateSlidesProgress(e), r && !l && t.emit("reachBeginning toEdge"), a && !o && t.emit("reachEnd toEdge"), (l && !r || o && !a) && t.emit("fromEdge"), t.emit("progress", n)
      },
      updateSlidesClasses: function () {
        const e = this,
          {
            slides: t,
            params: s,
            $wrapperEl: i,
            activeIndex: n,
            realIndex: r
          } = e,
          a = e.virtual && s.virtual.enabled;
        let l;
        t.removeClass(`${s.slideActiveClass} ${s.slideNextClass} ${s.slidePrevClass} ${s.slideDuplicateActiveClass} ${s.slideDuplicateNextClass} ${s.slideDuplicatePrevClass}`), l = a ? e.$wrapperEl.find(`.${s.slideClass}[data-swiper-slide-index="${n}"]`) : t.eq(n), l.addClass(s.slideActiveClass), s.loop && (l.hasClass(s.slideDuplicateClass) ? i.children(`.${s.slideClass}:not(.${s.slideDuplicateClass})[data-swiper-slide-index="${r}"]`).addClass(s.slideDuplicateActiveClass) : i.children(`.${s.slideClass}.${s.slideDuplicateClass}[data-swiper-slide-index="${r}"]`).addClass(s.slideDuplicateActiveClass));
        let o = l.nextAll(`.${s.slideClass}`).eq(0).addClass(s.slideNextClass);
        s.loop && 0 === o.length && (o = t.eq(0), o.addClass(s.slideNextClass));
        let d = l.prevAll(`.${s.slideClass}`).eq(0).addClass(s.slidePrevClass);
        s.loop && 0 === d.length && (d = t.eq(-1), d.addClass(s.slidePrevClass)), s.loop && (o.hasClass(s.slideDuplicateClass) ? i.children(`.${s.slideClass}:not(.${s.slideDuplicateClass})[data-swiper-slide-index="${o.attr("data-swiper-slide-index")}"]`).addClass(s.slideDuplicateNextClass) : i.children(`.${s.slideClass}.${s.slideDuplicateClass}[data-swiper-slide-index="${o.attr("data-swiper-slide-index")}"]`).addClass(s.slideDuplicateNextClass), d.hasClass(s.slideDuplicateClass) ? i.children(`.${s.slideClass}:not(.${s.slideDuplicateClass})[data-swiper-slide-index="${d.attr("data-swiper-slide-index")}"]`).addClass(s.slideDuplicatePrevClass) : i.children(`.${s.slideClass}.${s.slideDuplicateClass}[data-swiper-slide-index="${d.attr("data-swiper-slide-index")}"]`).addClass(s.slideDuplicatePrevClass)), e.emitSlidesClasses()
      },
      updateActiveIndex: function (e) {
        const t = this,
          s = t.rtlTranslate ? t.translate : -t.translate,
          {
            slidesGrid: i,
            snapGrid: n,
            params: r,
            activeIndex: a,
            realIndex: l,
            snapIndex: o
          } = t;
        let d, c = e;
        if (void 0 === c) {
          for (let e = 0; e < i.length; e += 1) void 0 !== i[e + 1] ? s >= i[e] && s < i[e + 1] - (i[e + 1] - i[e]) / 2 ? c = e : s >= i[e] && s < i[e + 1] && (c = e + 1) : s >= i[e] && (c = e);
          r.normalizeSlideIndex && (c < 0 || void 0 === c) && (c = 0)
        }
        if (n.indexOf(s) >= 0) d = n.indexOf(s);
        else {
          const e = Math.min(r.slidesPerGroupSkip, c);
          d = e + Math.floor((c - e) / r.slidesPerGroup)
        }
        if (d >= n.length && (d = n.length - 1), c === a) return void(d !== o && (t.snapIndex = d, t.emit("snapIndexChange")));
        const p = parseInt(t.slides.eq(c).attr("data-swiper-slide-index") || c, 10);
        Object.assign(t, {
          snapIndex: d,
          realIndex: p,
          previousIndex: a,
          activeIndex: c
        }), t.emit("activeIndexChange"), t.emit("snapIndexChange"), l !== p && t.emit("realIndexChange"), (t.initialized || t.params.runCallbacksOnInit) && t.emit("slideChange")
      },
      updateClickedSlide: function (e) {
        const t = this,
          s = t.params,
          i = d(e).closest(`.${s.slideClass}`)[0];
        let n, r = !1;
        if (i)
          for (let e = 0; e < t.slides.length; e += 1)
            if (t.slides[e] === i) {
              r = !0, n = e;
              break
            } if (!i || !r) return t.clickedSlide = void 0, void(t.clickedIndex = void 0);
        t.clickedSlide = i, t.virtual && t.params.virtual.enabled ? t.clickedIndex = parseInt(d(i).attr("data-swiper-slide-index"), 10) : t.clickedIndex = n, s.slideToClickedSlide && void 0 !== t.clickedIndex && t.clickedIndex !== t.activeIndex && t.slideToClickedSlide()
      }
    },
    E = {
      getTranslate: function (e) {
        void 0 === e && (e = this.isHorizontal() ? "x" : "y");
        const {
          params: t,
          rtlTranslate: s,
          translate: i,
          $wrapperEl: n
        } = this;
        if (t.virtualTranslate) return s ? -i : i;
        if (t.cssMode) return i;
        let a = function (e, t) {
          void 0 === t && (t = "x");
          const s = r();
          let i, n, a;
          const l = function (e) {
            const t = r();
            let s;
            return t.getComputedStyle && (s = t.getComputedStyle(e, null)), !s && e.currentStyle && (s = e.currentStyle), s || (s = e.style), s
          }(e);
          return s.WebKitCSSMatrix ? (n = l.transform || l.webkitTransform, n.split(",").length > 6 && (n = n.split(", ").map((e => e.replace(",", "."))).join(", ")), a = new s.WebKitCSSMatrix("none" === n ? "" : n)) : (a = l.MozTransform || l.OTransform || l.MsTransform || l.msTransform || l.transform || l.getPropertyValue("transform").replace("translate(", "matrix(1, 0, 0, 1,"), i = a.toString().split(",")), "x" === t && (n = s.WebKitCSSMatrix ? a.m41 : 16 === i.length ? parseFloat(i[12]) : parseFloat(i[4])), "y" === t && (n = s.WebKitCSSMatrix ? a.m42 : 16 === i.length ? parseFloat(i[13]) : parseFloat(i[5])), n || 0
        }(n[0], e);
        return s && (a = -a), a || 0
      },
      setTranslate: function (e, t) {
        const s = this,
          {
            rtlTranslate: i,
            params: n,
            $wrapperEl: r,
            wrapperEl: a,
            progress: l
          } = s;
        let o, d = 0,
          c = 0;
        s.isHorizontal() ? d = i ? -e : e : c = e, n.roundLengths && (d = Math.floor(d), c = Math.floor(c)), n.cssMode ? a[s.isHorizontal() ? "scrollLeft" : "scrollTop"] = s.isHorizontal() ? -d : -c : n.virtualTranslate || r.transform(`translate3d(${d}px, ${c}px, 0px)`), s.previousTranslate = s.translate, s.translate = s.isHorizontal() ? d : c;
        const p = s.maxTranslate() - s.minTranslate();
        o = 0 === p ? 0 : (e - s.minTranslate()) / p, o !== l && s.updateProgress(e), s.emit("setTranslate", s.translate, t)
      },
      minTranslate: function () {
        return -this.snapGrid[0]
      },
      maxTranslate: function () {
        return -this.snapGrid[this.snapGrid.length - 1]
      },
      translateTo: function (e, t, s, i, n) {
        void 0 === e && (e = 0), void 0 === t && (t = this.params.speed), void 0 === s && (s = !0), void 0 === i && (i = !0);
        const r = this,
          {
            params: a,
            wrapperEl: l
          } = r;
        if (r.animating && a.preventInteractionOnTransition) return !1;
        const o = r.minTranslate(),
          d = r.maxTranslate();
        let c;
        if (c = i && e > o ? o : i && e < d ? d : e, r.updateProgress(c), a.cssMode) {
          const e = r.isHorizontal();
          if (0 === t) l[e ? "scrollLeft" : "scrollTop"] = -c;
          else {
            if (!r.support.smoothScroll) return v({
              swiper: r,
              targetPosition: -c,
              side: e ? "left" : "top"
            }), !0;
            l.scrollTo({
              [e ? "left" : "top"]: -c,
              behavior: "smooth"
            })
          }
          return !0
        }
        return 0 === t ? (r.setTransition(0), r.setTranslate(c), s && (r.emit("beforeTransitionStart", t, n), r.emit("transitionEnd"))) : (r.setTransition(t), r.setTranslate(c), s && (r.emit("beforeTransitionStart", t, n), r.emit("transitionStart")), r.animating || (r.animating = !0, r.onTranslateToWrapperTransitionEnd || (r.onTranslateToWrapperTransitionEnd = function (e) {
          !r || r.destroyed || e.target === this && (r.$wrapperEl[0].removeEventListener("transitionend", r.onTranslateToWrapperTransitionEnd), r.$wrapperEl[0].removeEventListener("webkitTransitionEnd", r.onTranslateToWrapperTransitionEnd), r.onTranslateToWrapperTransitionEnd = null, delete r.onTranslateToWrapperTransitionEnd, s && r.emit("transitionEnd"))
        }), r.$wrapperEl[0].addEventListener("transitionend", r.onTranslateToWrapperTransitionEnd), r.$wrapperEl[0].addEventListener("webkitTransitionEnd", r.onTranslateToWrapperTransitionEnd))), !0
      }
    };

  function x(e) {
    let {
      swiper: t,
      runCallbacks: s,
      direction: i,
      step: n
    } = e;
    const {
      activeIndex: r,
      previousIndex: a
    } = t;
    let l = i;
    if (l || (l = r > a ? "next" : r < a ? "prev" : "reset"), t.emit(`transition${n}`), s && r !== a) {
      if ("reset" === l) return void t.emit(`slideResetTransition${n}`);
      t.emit(`slideChangeTransition${n}`), "next" === l ? t.emit(`slideNextTransition${n}`) : t.emit(`slidePrevTransition${n}`)
    }
  }
  var M = {
      slideTo: function (e, t, s, i, n) {
        if (void 0 === e && (e = 0), void 0 === t && (t = this.params.speed), void 0 === s && (s = !0), "number" != typeof e && "string" != typeof e) throw new Error(`The 'index' argument cannot have type other than 'number' or 'string'. [${typeof e}] given.`);
        if ("string" == typeof e) {
          const t = parseInt(e, 10);
          if (!isFinite(t)) throw new Error(`The passed-in 'index' (string) couldn't be converted to 'number'. [${e}] given.`);
          e = t
        }
        const r = this;
        let a = e;
        a < 0 && (a = 0);
        const {
          params: l,
          snapGrid: o,
          slidesGrid: d,
          previousIndex: c,
          activeIndex: p,
          rtlTranslate: u,
          wrapperEl: h,
          enabled: f
        } = r;
        if (r.animating && l.preventInteractionOnTransition || !f && !i && !n) return !1;
        const m = Math.min(r.params.slidesPerGroupSkip, a);
        let g = m + Math.floor((a - m) / r.params.slidesPerGroup);
        g >= o.length && (g = o.length - 1), (p || l.initialSlide || 0) === (c || 0) && s && r.emit("beforeSlideChangeStart");
        const w = -o[g];
        if (r.updateProgress(w), l.normalizeSlideIndex)
          for (let e = 0; e < d.length; e += 1) {
            const t = -Math.floor(100 * w),
              s = Math.floor(100 * d[e]),
              i = Math.floor(100 * d[e + 1]);
            void 0 !== d[e + 1] ? t >= s && t < i - (i - s) / 2 ? a = e : t >= s && t < i && (a = e + 1) : t >= s && (a = e)
          }
        if (r.initialized && a !== p && (!r.allowSlideNext && w < r.translate && w < r.minTranslate() || !r.allowSlidePrev && w > r.translate && w > r.maxTranslate() && (p || 0) !== a)) return !1;
        let b;
        if (b = a > p ? "next" : a < p ? "prev" : "reset", u && -w === r.translate || !u && w === r.translate) return r.updateActiveIndex(a), l.autoHeight && r.updateAutoHeight(), r.updateSlidesClasses(), "slide" !== l.effect && r.setTranslate(w), "reset" !== b && (r.transitionStart(s, b), r.transitionEnd(s, b)), !1;
        if (l.cssMode) {
          const e = r.isHorizontal(),
            s = u ? w : -w;
          if (0 === t) {
            const t = r.virtual && r.params.virtual.enabled;
            t && (r.wrapperEl.style.scrollSnapType = "none", r._immediateVirtual = !0), h[e ? "scrollLeft" : "scrollTop"] = s, t && requestAnimationFrame((() => {
              r.wrapperEl.style.scrollSnapType = "", r._swiperImmediateVirtual = !1
            }))
          } else {
            if (!r.support.smoothScroll) return v({
              swiper: r,
              targetPosition: s,
              side: e ? "left" : "top"
            }), !0;
            h.scrollTo({
              [e ? "left" : "top"]: s,
              behavior: "smooth"
            })
          }
          return !0
        }
        return r.setTransition(t), r.setTranslate(w), r.updateActiveIndex(a), r.updateSlidesClasses(), r.emit("beforeTransitionStart", t, i), r.transitionStart(s, b), 0 === t ? r.transitionEnd(s, b) : r.animating || (r.animating = !0, r.onSlideToWrapperTransitionEnd || (r.onSlideToWrapperTransitionEnd = function (e) {
          !r || r.destroyed || e.target === this && (r.$wrapperEl[0].removeEventListener("transitionend", r.onSlideToWrapperTransitionEnd), r.$wrapperEl[0].removeEventListener("webkitTransitionEnd", r.onSlideToWrapperTransitionEnd), r.onSlideToWrapperTransitionEnd = null, delete r.onSlideToWrapperTransitionEnd, r.transitionEnd(s, b))
        }), r.$wrapperEl[0].addEventListener("transitionend", r.onSlideToWrapperTransitionEnd), r.$wrapperEl[0].addEventListener("webkitTransitionEnd", r.onSlideToWrapperTransitionEnd)), !0
      },
      slideToLoop: function (e, t, s, i) {
        void 0 === e && (e = 0), void 0 === t && (t = this.params.speed), void 0 === s && (s = !0);
        const n = this;
        let r = e;
        return n.params.loop && (r += n.loopedSlides), n.slideTo(r, t, s, i)
      },
      slideNext: function (e, t, s) {
        void 0 === e && (e = this.params.speed), void 0 === t && (t = !0);
        const i = this,
          {
            animating: n,
            enabled: r,
            params: a
          } = i;
        if (!r) return i;
        let l = a.slidesPerGroup;
        "auto" === a.slidesPerView && 1 === a.slidesPerGroup && a.slidesPerGroupAuto && (l = Math.max(i.slidesPerViewDynamic("current", !0), 1));
        const o = i.activeIndex < a.slidesPerGroupSkip ? 1 : l;
        if (a.loop) {
          if (n && a.loopPreventsSlide) return !1;
          i.loopFix(), i._clientLeft = i.$wrapperEl[0].clientLeft
        }
        return a.rewind && i.isEnd ? i.slideTo(0, e, t, s) : i.slideTo(i.activeIndex + o, e, t, s)
      },
      slidePrev: function (e, t, s) {
        void 0 === e && (e = this.params.speed), void 0 === t && (t = !0);
        const i = this,
          {
            params: n,
            animating: r,
            snapGrid: a,
            slidesGrid: l,
            rtlTranslate: o,
            enabled: d
          } = i;
        if (!d) return i;
        if (n.loop) {
          if (r && n.loopPreventsSlide) return !1;
          i.loopFix(), i._clientLeft = i.$wrapperEl[0].clientLeft
        }

        function c(e) {
          return e < 0 ? -Math.floor(Math.abs(e)) : Math.floor(e)
        }
        const p = c(o ? i.translate : -i.translate),
          u = a.map((e => c(e)));
        let h = a[u.indexOf(p) - 1];
        if (void 0 === h && n.cssMode) {
          let e;
          a.forEach(((t, s) => {
            p >= t && (e = s)
          })), void 0 !== e && (h = a[e > 0 ? e - 1 : e])
        }
        let f = 0;
        if (void 0 !== h && (f = l.indexOf(h), f < 0 && (f = i.activeIndex - 1), "auto" === n.slidesPerView && 1 === n.slidesPerGroup && n.slidesPerGroupAuto && (f = f - i.slidesPerViewDynamic("previous", !0) + 1, f = Math.max(f, 0))), n.rewind && i.isBeginning) {
          const n = i.params.virtual && i.params.virtual.enabled && i.virtual ? i.virtual.slides.length - 1 : i.slides.length - 1;
          return i.slideTo(n, e, t, s)
        }
        return i.slideTo(f, e, t, s)
      },
      slideReset: function (e, t, s) {
        return void 0 === e && (e = this.params.speed), void 0 === t && (t = !0), this.slideTo(this.activeIndex, e, t, s)
      },
      slideToClosest: function (e, t, s, i) {
        void 0 === e && (e = this.params.speed), void 0 === t && (t = !0), void 0 === i && (i = .5);
        const n = this;
        let r = n.activeIndex;
        const a = Math.min(n.params.slidesPerGroupSkip, r),
          l = a + Math.floor((r - a) / n.params.slidesPerGroup),
          o = n.rtlTranslate ? n.translate : -n.translate;
        if (o >= n.snapGrid[l]) {
          const e = n.snapGrid[l];
          o - e > (n.snapGrid[l + 1] - e) * i && (r += n.params.slidesPerGroup)
        } else {
          const e = n.snapGrid[l - 1];
          o - e <= (n.snapGrid[l] - e) * i && (r -= n.params.slidesPerGroup)
        }
        return r = Math.max(r, 0), r = Math.min(r, n.slidesGrid.length - 1), n.slideTo(r, e, t, s)
      },
      slideToClickedSlide: function () {
        const e = this,
          {
            params: t,
            $wrapperEl: s
          } = e,
          i = "auto" === t.slidesPerView ? e.slidesPerViewDynamic() : t.slidesPerView;
        let n, r = e.clickedIndex;
        if (t.loop) {
          if (e.animating) return;
          n = parseInt(d(e.clickedSlide).attr("data-swiper-slide-index"), 10), t.centeredSlides ? r < e.loopedSlides - i / 2 || r > e.slides.length - e.loopedSlides + i / 2 ? (e.loopFix(), r = s.children(`.${t.slideClass}[data-swiper-slide-index="${n}"]:not(.${t.slideDuplicateClass})`).eq(0).index(), p((() => {
            e.slideTo(r)
          }))) : e.slideTo(r) : r > e.slides.length - i ? (e.loopFix(), r = s.children(`.${t.slideClass}[data-swiper-slide-index="${n}"]:not(.${t.slideDuplicateClass})`).eq(0).index(), p((() => {
            e.slideTo(r)
          }))) : e.slideTo(r)
        } else e.slideTo(r)
      }
    },
    $ = {
      loopCreate: function () {
        const e = this,
          t = i(),
          {
            params: s,
            $wrapperEl: n
          } = e,
          r = n.children().length > 0 ? d(n.children()[0].parentNode) : n;
        r.children(`.${s.slideClass}.${s.slideDuplicateClass}`).remove();
        let a = r.children(`.${s.slideClass}`);
        if (s.loopFillGroupWithBlank) {
          const e = s.slidesPerGroup - a.length % s.slidesPerGroup;
          if (e !== s.slidesPerGroup) {
            for (let i = 0; i < e; i += 1) {
              const e = d(t.createElement("div")).addClass(`${s.slideClass} ${s.slideBlankClass}`);
              r.append(e)
            }
            a = r.children(`.${s.slideClass}`)
          }
        }
        "auto" === s.slidesPerView && !s.loopedSlides && (s.loopedSlides = a.length), e.loopedSlides = Math.ceil(parseFloat(s.loopedSlides || s.slidesPerView, 10)), e.loopedSlides += s.loopAdditionalSlides, e.loopedSlides > a.length && (e.loopedSlides = a.length);
        const l = [],
          o = [];
        a.each(((t, s) => {
          const i = d(t);
          s < e.loopedSlides && o.push(t), s < a.length && s >= a.length - e.loopedSlides && l.push(t), i.attr("data-swiper-slide-index", s)
        }));
        for (let e = 0; e < o.length; e += 1) r.append(d(o[e].cloneNode(!0)).addClass(s.slideDuplicateClass));
        for (let e = l.length - 1; e >= 0; e -= 1) r.prepend(d(l[e].cloneNode(!0)).addClass(s.slideDuplicateClass))
      },
      loopFix: function () {
        const e = this;
        e.emit("beforeLoopFix");
        const {
          activeIndex: t,
          slides: s,
          loopedSlides: i,
          allowSlidePrev: n,
          allowSlideNext: r,
          snapGrid: a,
          rtlTranslate: l
        } = e;
        let o;
        e.allowSlidePrev = !0, e.allowSlideNext = !0;
        const d = -a[t] - e.getTranslate();
        t < i ? (o = s.length - 3 * i + t, o += i, e.slideTo(o, 0, !1, !0) && 0 !== d && e.setTranslate((l ? -e.translate : e.translate) - d)) : t >= s.length - i && (o = -s.length + t + i, o += i, e.slideTo(o, 0, !1, !0) && 0 !== d && e.setTranslate((l ? -e.translate : e.translate) - d)), e.allowSlidePrev = n, e.allowSlideNext = r, e.emit("loopFix")
      },
      loopDestroy: function () {
        const {
          $wrapperEl: e,
          params: t,
          slides: s
        } = this;
        e.children(`.${t.slideClass}.${t.slideDuplicateClass},.${t.slideClass}.${t.slideBlankClass}`).remove(), s.removeAttr("data-swiper-slide-index")
      }
    };

  function P(e) {
    const t = this,
      s = i(),
      n = r(),
      a = t.touchEventsData,
      {
        params: l,
        touches: o,
        enabled: c
      } = t;
    if (!c || t.animating && l.preventInteractionOnTransition) return;
    !t.animating && l.cssMode && l.loop && t.loopFix();
    let p = e;
    p.originalEvent && (p = p.originalEvent);
    let h = d(p.target);
    if ("wrapper" === l.touchEventsTarget && !h.closest(t.wrapperEl).length || (a.isTouchEvent = "touchstart" === p.type, !a.isTouchEvent && "which" in p && 3 === p.which) || !a.isTouchEvent && "button" in p && p.button > 0 || a.isTouched && a.isMoved) return;
    l.noSwipingClass && "" !== l.noSwipingClass && p.target && p.target.shadowRoot && e.path && e.path[0] && (h = d(e.path[0]));
    const f = l.noSwipingSelector ? l.noSwipingSelector : `.${l.noSwipingClass}`,
      m = !(!p.target || !p.target.shadowRoot);
    if (l.noSwiping && (m ? function (e, t) {
        return void 0 === t && (t = this),
          function t(s) {
            return s && s !== i() && s !== r() ? (s.assignedSlot && (s = s.assignedSlot), s.closest(e) || t(s.getRootNode().host)) : null
          }(t)
      }(f, p.target) : h.closest(f)[0])) return void(t.allowClick = !0);
    if (l.swipeHandler && !h.closest(l.swipeHandler)[0]) return;
    o.currentX = "touchstart" === p.type ? p.targetTouches[0].pageX : p.pageX, o.currentY = "touchstart" === p.type ? p.targetTouches[0].pageY : p.pageY;
    const g = o.currentX,
      v = o.currentY,
      w = l.edgeSwipeDetection || l.iOSEdgeSwipeDetection,
      b = l.edgeSwipeThreshold || l.iOSEdgeSwipeThreshold;
    if (w && (g <= b || g >= n.innerWidth - b)) {
      if ("prevent" !== w) return;
      e.preventDefault()
    }
    if (Object.assign(a, {
        isTouched: !0,
        isMoved: !1,
        allowTouchCallbacks: !0,
        isScrolling: void 0,
        startMoving: void 0
      }), o.startX = g, o.startY = v, a.touchStartTime = u(), t.allowClick = !0, t.updateSize(), t.swipeDirection = void 0, l.threshold > 0 && (a.allowThresholdMove = !1), "touchstart" !== p.type) {
      let e = !0;
      h.is(a.focusableElements) && (e = !1, "SELECT" === h[0].nodeName && (a.isTouched = !1)), s.activeElement && d(s.activeElement).is(a.focusableElements) && s.activeElement !== h[0] && s.activeElement.blur();
      const i = e && t.allowTouchMove && l.touchStartPreventDefault;
      (l.touchStartForcePreventDefault || i) && !h[0].isContentEditable && p.preventDefault()
    }
    t.params.freeMode && t.params.freeMode.enabled && t.freeMode && t.animating && !l.cssMode && t.freeMode.onTouchStart(), t.emit("touchStart", p)
  }

  function k(e) {
    const t = i(),
      s = this,
      n = s.touchEventsData,
      {
        params: r,
        touches: a,
        rtlTranslate: l,
        enabled: o
      } = s;
    if (!o) return;
    let c = e;
    if (c.originalEvent && (c = c.originalEvent), !n.isTouched) return void(n.startMoving && n.isScrolling && s.emit("touchMoveOpposite", c));
    if (n.isTouchEvent && "touchmove" !== c.type) return;
    const p = "touchmove" === c.type && c.targetTouches && (c.targetTouches[0] || c.changedTouches[0]),
      h = "touchmove" === c.type ? p.pageX : c.pageX,
      f = "touchmove" === c.type ? p.pageY : c.pageY;
    if (c.preventedByNestedSwiper) return a.startX = h, void(a.startY = f);
    if (!s.allowTouchMove) return d(c.target).is(n.focusableElements) || (s.allowClick = !1), void(n.isTouched && (Object.assign(a, {
      startX: h,
      startY: f,
      currentX: h,
      currentY: f
    }), n.touchStartTime = u()));
    if (n.isTouchEvent && r.touchReleaseOnEdges && !r.loop)
      if (s.isVertical()) {
        if (f < a.startY && s.translate <= s.maxTranslate() || f > a.startY && s.translate >= s.minTranslate()) return n.isTouched = !1, void(n.isMoved = !1)
      } else if (h < a.startX && s.translate <= s.maxTranslate() || h > a.startX && s.translate >= s.minTranslate()) return;
    if (n.isTouchEvent && t.activeElement && c.target === t.activeElement && d(c.target).is(n.focusableElements)) return n.isMoved = !0, void(s.allowClick = !1);
    if (n.allowTouchCallbacks && s.emit("touchMove", c), c.targetTouches && c.targetTouches.length > 1) return;
    a.currentX = h, a.currentY = f;
    const m = a.currentX - a.startX,
      g = a.currentY - a.startY;
    if (s.params.threshold && Math.sqrt(m ** 2 + g ** 2) < s.params.threshold) return;
    if (void 0 === n.isScrolling) {
      let e;
      s.isHorizontal() && a.currentY === a.startY || s.isVertical() && a.currentX === a.startX ? n.isScrolling = !1 : m * m + g * g >= 25 && (e = 180 * Math.atan2(Math.abs(g), Math.abs(m)) / Math.PI, n.isScrolling = s.isHorizontal() ? e > r.touchAngle : 90 - e > r.touchAngle)
    }
    if (n.isScrolling && s.emit("touchMoveOpposite", c), void 0 === n.startMoving && (a.currentX !== a.startX || a.currentY !== a.startY) && (n.startMoving = !0), n.isScrolling) return void(n.isTouched = !1);
    if (!n.startMoving) return;
    s.allowClick = !1, !r.cssMode && c.cancelable && c.preventDefault(), r.touchMoveStopPropagation && !r.nested && c.stopPropagation(), n.isMoved || (r.loop && !r.cssMode && s.loopFix(), n.startTranslate = s.getTranslate(), s.setTransition(0), s.animating && s.$wrapperEl.trigger("webkitTransitionEnd transitionend"), n.allowMomentumBounce = !1, r.grabCursor && (!0 === s.allowSlideNext || !0 === s.allowSlidePrev) && s.setGrabCursor(!0), s.emit("sliderFirstMove", c)), s.emit("sliderMove", c), n.isMoved = !0;
    let v = s.isHorizontal() ? m : g;
    a.diff = v, v *= r.touchRatio, l && (v = -v), s.swipeDirection = v > 0 ? "prev" : "next", n.currentTranslate = v + n.startTranslate;
    let w = !0,
      b = r.resistanceRatio;
    if (r.touchReleaseOnEdges && (b = 0), v > 0 && n.currentTranslate > s.minTranslate() ? (w = !1, r.resistance && (n.currentTranslate = s.minTranslate() - 1 + (-s.minTranslate() + n.startTranslate + v) ** b)) : v < 0 && n.currentTranslate < s.maxTranslate() && (w = !1, r.resistance && (n.currentTranslate = s.maxTranslate() + 1 - (s.maxTranslate() - n.startTranslate - v) ** b)), w && (c.preventedByNestedSwiper = !0), !s.allowSlideNext && "next" === s.swipeDirection && n.currentTranslate < n.startTranslate && (n.currentTranslate = n.startTranslate), !s.allowSlidePrev && "prev" === s.swipeDirection && n.currentTranslate > n.startTranslate && (n.currentTranslate = n.startTranslate), !s.allowSlidePrev && !s.allowSlideNext && (n.currentTranslate = n.startTranslate), r.threshold > 0) {
      if (!(Math.abs(v) > r.threshold || n.allowThresholdMove)) return void(n.currentTranslate = n.startTranslate);
      if (!n.allowThresholdMove) return n.allowThresholdMove = !0, a.startX = a.currentX, a.startY = a.currentY, n.currentTranslate = n.startTranslate, void(a.diff = s.isHorizontal() ? a.currentX - a.startX : a.currentY - a.startY)
    }!r.followFinger || r.cssMode || ((r.freeMode && r.freeMode.enabled && s.freeMode || r.watchSlidesProgress) && (s.updateActiveIndex(), s.updateSlidesClasses()), s.params.freeMode && r.freeMode.enabled && s.freeMode && s.freeMode.onTouchMove(), s.updateProgress(n.currentTranslate), s.setTranslate(n.currentTranslate))
  }

  function L(e) {
    const t = this,
      s = t.touchEventsData,
      {
        params: i,
        touches: n,
        rtlTranslate: r,
        slidesGrid: a,
        enabled: l
      } = t;
    if (!l) return;
    let o = e;
    if (o.originalEvent && (o = o.originalEvent), s.allowTouchCallbacks && t.emit("touchEnd", o), s.allowTouchCallbacks = !1, !s.isTouched) return s.isMoved && i.grabCursor && t.setGrabCursor(!1), s.isMoved = !1, void(s.startMoving = !1);
    i.grabCursor && s.isMoved && s.isTouched && (!0 === t.allowSlideNext || !0 === t.allowSlidePrev) && t.setGrabCursor(!1);
    const d = u(),
      c = d - s.touchStartTime;
    if (t.allowClick) {
      const e = o.path || o.composedPath && o.composedPath();
      t.updateClickedSlide(e && e[0] || o.target), t.emit("tap click", o), c < 300 && d - s.lastClickTime < 300 && t.emit("doubleTap doubleClick", o)
    }
    if (s.lastClickTime = u(), p((() => {
        t.destroyed || (t.allowClick = !0)
      })), !s.isTouched || !s.isMoved || !t.swipeDirection || 0 === n.diff || s.currentTranslate === s.startTranslate) return s.isTouched = !1, s.isMoved = !1, void(s.startMoving = !1);
    let h;
    if (s.isTouched = !1, s.isMoved = !1, s.startMoving = !1, h = i.followFinger ? r ? t.translate : -t.translate : -s.currentTranslate, i.cssMode) return;
    if (t.params.freeMode && i.freeMode.enabled) return void t.freeMode.onTouchEnd({
      currentPos: h
    });
    let f = 0,
      m = t.slidesSizesGrid[0];
    for (let e = 0; e < a.length; e += e < i.slidesPerGroupSkip ? 1 : i.slidesPerGroup) {
      const t = e < i.slidesPerGroupSkip - 1 ? 1 : i.slidesPerGroup;
      void 0 !== a[e + t] ? h >= a[e] && h < a[e + t] && (f = e, m = a[e + t] - a[e]) : h >= a[e] && (f = e, m = a[a.length - 1] - a[a.length - 2])
    }
    let g = null,
      v = null;
    i.rewind && (t.isBeginning ? v = t.params.virtual && t.params.virtual.enabled && t.virtual ? t.virtual.slides.length - 1 : t.slides.length - 1 : t.isEnd && (g = 0));
    const w = (h - a[f]) / m,
      b = f < i.slidesPerGroupSkip - 1 ? 1 : i.slidesPerGroup;
    if (c > i.longSwipesMs) {
      if (!i.longSwipes) return void t.slideTo(t.activeIndex);
      "next" === t.swipeDirection && (w >= i.longSwipesRatio ? t.slideTo(i.rewind && t.isEnd ? g : f + b) : t.slideTo(f)), "prev" === t.swipeDirection && (w > 1 - i.longSwipesRatio ? t.slideTo(f + b) : null !== v && w < 0 && Math.abs(w) > i.longSwipesRatio ? t.slideTo(v) : t.slideTo(f))
    } else {
      if (!i.shortSwipes) return void t.slideTo(t.activeIndex);
      !t.navigation || o.target !== t.navigation.nextEl && o.target !== t.navigation.prevEl ? ("next" === t.swipeDirection && t.slideTo(null !== g ? g : f + b), "prev" === t.swipeDirection && t.slideTo(null !== v ? v : f)) : o.target === t.navigation.nextEl ? t.slideTo(f + b) : t.slideTo(f)
    }
  }

  function O() {
    const e = this,
      {
        params: t,
        el: s
      } = e;
    if (s && 0 === s.offsetWidth) return;
    t.breakpoints && e.setBreakpoint();
    const {
      allowSlideNext: i,
      allowSlidePrev: n,
      snapGrid: r
    } = e;
    e.allowSlideNext = !0, e.allowSlidePrev = !0, e.updateSize(), e.updateSlides(), e.updateSlidesClasses(), ("auto" === t.slidesPerView || t.slidesPerView > 1) && e.isEnd && !e.isBeginning && !e.params.centeredSlides ? e.slideTo(e.slides.length - 1, 0, !1, !0) : e.slideTo(e.activeIndex, 0, !1, !0), e.autoplay && e.autoplay.running && e.autoplay.paused && e.autoplay.run(), e.allowSlidePrev = n, e.allowSlideNext = i, e.params.watchOverflow && r !== e.snapGrid && e.checkOverflow()
  }

  function A(e) {
    const t = this;
    !t.enabled || t.allowClick || (t.params.preventClicks && e.preventDefault(), t.params.preventClicksPropagation && t.animating && (e.stopPropagation(), e.stopImmediatePropagation()))
  }

  function z() {
    const e = this,
      {
        wrapperEl: t,
        rtlTranslate: s,
        enabled: i
      } = e;
    if (!i) return;
    let n;
    e.previousTranslate = e.translate, e.isHorizontal() ? e.translate = -t.scrollLeft : e.translate = -t.scrollTop, -0 === e.translate && (e.translate = 0), e.updateActiveIndex(), e.updateSlidesClasses();
    const r = e.maxTranslate() - e.minTranslate();
    n = 0 === r ? 0 : (e.translate - e.minTranslate()) / r, n !== e.progress && e.updateProgress(s ? -e.translate : e.translate), e.emit("setTranslate", e.translate, !1)
  }
  let I = !1;

  function D() {}
  const G = (e, t) => {
      const s = i(),
        {
          params: n,
          touchEvents: r,
          el: a,
          wrapperEl: l,
          device: o,
          support: d
        } = e,
        c = !!n.nested,
        p = "on" === t ? "addEventListener" : "removeEventListener",
        u = t;
      if (d.touch) {
        const t = !("touchstart" !== r.start || !d.passiveListener || !n.passiveListeners) && {
          passive: !0,
          capture: !1
        };
        a[p](r.start, e.onTouchStart, t), a[p](r.move, e.onTouchMove, d.passiveListener ? {
          passive: !1,
          capture: c
        } : c), a[p](r.end, e.onTouchEnd, t), r.cancel && a[p](r.cancel, e.onTouchEnd, t)
      } else a[p](r.start, e.onTouchStart, !1), s[p](r.move, e.onTouchMove, c), s[p](r.end, e.onTouchEnd, !1);
      (n.preventClicks || n.preventClicksPropagation) && a[p]("click", e.onClick, !0), n.cssMode && l[p]("scroll", e.onScroll), n.updateOnWindowResize ? e[u](o.ios || o.android ? "resize orientationchange observerUpdate" : "resize observerUpdate", O, !0) : e[u]("observerUpdate", O, !0)
    },
    B = (e, t) => e.grid && t.grid && t.grid.rows > 1;
  var N = {
      setBreakpoint: function () {
        const e = this,
          {
            activeIndex: t,
            initialized: s,
            loopedSlides: i = 0,
            params: n,
            $el: r
          } = e,
          a = n.breakpoints;
        if (!a || a && 0 === Object.keys(a).length) return;
        const l = e.getBreakpoint(a, e.params.breakpointsBase, e.el);
        if (!l || e.currentBreakpoint === l) return;
        const o = (l in a ? a[l] : void 0) || e.originalParams,
          d = B(e, n),
          c = B(e, o),
          p = n.enabled;
        d && !c ? (r.removeClass(`${n.containerModifierClass}grid ${n.containerModifierClass}grid-column`), e.emitContainerClasses()) : !d && c && (r.addClass(`${n.containerModifierClass}grid`), (o.grid.fill && "column" === o.grid.fill || !o.grid.fill && "column" === n.grid.fill) && r.addClass(`${n.containerModifierClass}grid-column`), e.emitContainerClasses());
        const u = o.direction && o.direction !== n.direction,
          h = n.loop && (o.slidesPerView !== n.slidesPerView || u);
        u && s && e.changeDirection(), m(e.params, o);
        const f = e.params.enabled;
        Object.assign(e, {
          allowTouchMove: e.params.allowTouchMove,
          allowSlideNext: e.params.allowSlideNext,
          allowSlidePrev: e.params.allowSlidePrev
        }), p && !f ? e.disable() : !p && f && e.enable(), e.currentBreakpoint = l, e.emit("_beforeBreakpoint", o), h && s && (e.loopDestroy(), e.loopCreate(), e.updateSlides(), e.slideTo(t - i + e.loopedSlides, 0, !1)), e.emit("breakpoint", o)
      },
      getBreakpoint: function (e, t, s) {
        if (void 0 === t && (t = "window"), !e || "container" === t && !s) return;
        let i = !1;
        const n = r(),
          a = "window" === t ? n.innerHeight : s.clientHeight,
          l = Object.keys(e).map((e => {
            if ("string" == typeof e && 0 === e.indexOf("@")) {
              const t = parseFloat(e.substr(1));
              return {
                value: a * t,
                point: e
              }
            }
            return {
              value: e,
              point: e
            }
          }));
        l.sort(((e, t) => parseInt(e.value, 10) - parseInt(t.value, 10)));
        for (let e = 0; e < l.length; e += 1) {
          const {
            point: r,
            value: a
          } = l[e];
          "window" === t ? n.matchMedia(`(min-width: ${a}px)`).matches && (i = r) : a <= s.clientWidth && (i = r)
        }
        return i || "max"
      }
    },
    _ = {
      init: !0,
      direction: "horizontal",
      touchEventsTarget: "wrapper",
      initialSlide: 0,
      speed: 300,
      cssMode: !1,
      updateOnWindowResize: !0,
      resizeObserver: !0,
      nested: !1,
      createElements: !1,
      enabled: !0,
      focusableElements: "input, select, option, textarea, button, video, label",
      width: null,
      height: null,
      preventInteractionOnTransition: !1,
      userAgent: null,
      url: null,
      edgeSwipeDetection: !1,
      edgeSwipeThreshold: 20,
      autoHeight: !1,
      setWrapperSize: !1,
      virtualTranslate: !1,
      effect: "slide",
      breakpoints: void 0,
      breakpointsBase: "window",
      spaceBetween: 0,
      slidesPerView: 1,
      slidesPerGroup: 1,
      slidesPerGroupSkip: 0,
      slidesPerGroupAuto: !1,
      centeredSlides: !1,
      centeredSlidesBounds: !1,
      slidesOffsetBefore: 0,
      slidesOffsetAfter: 0,
      normalizeSlideIndex: !0,
      centerInsufficientSlides: !1,
      watchOverflow: !0,
      roundLengths: !1,
      touchRatio: 1,
      touchAngle: 45,
      simulateTouch: !0,
      shortSwipes: !0,
      longSwipes: !0,
      longSwipesRatio: .5,
      longSwipesMs: 300,
      followFinger: !0,
      allowTouchMove: !0,
      threshold: 0,
      touchMoveStopPropagation: !1,
      touchStartPreventDefault: !0,
      touchStartForcePreventDefault: !1,
      touchReleaseOnEdges: !1,
      uniqueNavElements: !0,
      resistance: !0,
      resistanceRatio: .85,
      watchSlidesProgress: !1,
      grabCursor: !1,
      preventClicks: !0,
      preventClicksPropagation: !0,
      slideToClickedSlide: !1,
      preloadImages: !0,
      updateOnImagesReady: !0,
      loop: !1,
      loopAdditionalSlides: 0,
      loopedSlides: null,
      loopFillGroupWithBlank: !1,
      loopPreventsSlide: !0,
      rewind: !1,
      allowSlidePrev: !0,
      allowSlideNext: !0,
      swipeHandler: null,
      noSwiping: !0,
      noSwipingClass: "swiper-no-swiping",
      noSwipingSelector: null,
      passiveListeners: !0,
      maxBackfaceHiddenSlides: 10,
      containerModifierClass: "swiper-",
      slideClass: "swiper-slide",
      slideBlankClass: "swiper-slide-invisible-blank",
      slideActiveClass: "swiper-slide-active",
      slideDuplicateActiveClass: "swiper-slide-duplicate-active",
      slideVisibleClass: "swiper-slide-visible",
      slideDuplicateClass: "swiper-slide-duplicate",
      slideNextClass: "swiper-slide-next",
      slideDuplicateNextClass: "swiper-slide-duplicate-next",
      slidePrevClass: "swiper-slide-prev",
      slideDuplicatePrevClass: "swiper-slide-duplicate-prev",
      wrapperClass: "swiper-wrapper",
      runCallbacksOnInit: !0,
      _emitClasses: !1
    };

  function j(e, t) {
    return function (s) {
      void 0 === s && (s = {});
      const i = Object.keys(s)[0],
        n = s[i];
      "object" == typeof n && null !== n ? (["navigation", "pagination", "scrollbar"].indexOf(i) >= 0 && !0 === e[i] && (e[i] = {
        auto: !0
      }), i in e && "enabled" in n ? (!0 === e[i] && (e[i] = {
        enabled: !0
      }), "object" == typeof e[i] && !("enabled" in e[i]) && (e[i].enabled = !0), e[i] || (e[i] = {
        enabled: !1
      }), m(t, s)) : m(t, s)) : m(t, s)
    }
  }
  const H = {
      eventsEmitter: S,
      update: y,
      translate: E,
      transition: {
        setTransition: function (e, t) {
          const s = this;
          s.params.cssMode || s.$wrapperEl.transition(e), s.emit("setTransition", e, t)
        },
        transitionStart: function (e, t) {
          void 0 === e && (e = !0);
          const s = this,
            {
              params: i
            } = s;
          i.cssMode || (i.autoHeight && s.updateAutoHeight(), x({
            swiper: s,
            runCallbacks: e,
            direction: t,
            step: "Start"
          }))
        },
        transitionEnd: function (e, t) {
          void 0 === e && (e = !0);
          const s = this,
            {
              params: i
            } = s;
          s.animating = !1, !i.cssMode && (s.setTransition(0), x({
            swiper: s,
            runCallbacks: e,
            direction: t,
            step: "End"
          }))
        }
      },
      slide: M,
      loop: $,
      grabCursor: {
        setGrabCursor: function (e) {
          const t = this;
          if (t.support.touch || !t.params.simulateTouch || t.params.watchOverflow && t.isLocked || t.params.cssMode) return;
          const s = "container" === t.params.touchEventsTarget ? t.el : t.wrapperEl;
          s.style.cursor = "move", s.style.cursor = e ? "-webkit-grabbing" : "-webkit-grab", s.style.cursor = e ? "-moz-grabbin" : "-moz-grab", s.style.cursor = e ? "grabbing" : "grab"
        },
        unsetGrabCursor: function () {
          const e = this;
          e.support.touch || e.params.watchOverflow && e.isLocked || e.params.cssMode || (e["container" === e.params.touchEventsTarget ? "el" : "wrapperEl"].style.cursor = "")
        }
      },
      events: {
        attachEvents: function () {
          const e = this,
            t = i(),
            {
              params: s,
              support: n
            } = e;
          e.onTouchStart = P.bind(e), e.onTouchMove = k.bind(e), e.onTouchEnd = L.bind(e), s.cssMode && (e.onScroll = z.bind(e)), e.onClick = A.bind(e), n.touch && !I && (t.addEventListener("touchstart", D), I = !0), G(e, "on")
        },
        detachEvents: function () {
          G(this, "off")
        }
      },
      breakpoints: N,
      checkOverflow: {
        checkOverflow: function () {
          const e = this,
            {
              isLocked: t,
              params: s
            } = e,
            {
              slidesOffsetBefore: i
            } = s;
          if (i) {
            const t = e.slides.length - 1,
              s = e.slidesGrid[t] + e.slidesSizesGrid[t] + 2 * i;
            e.isLocked = e.size > s
          } else e.isLocked = 1 === e.snapGrid.length;
          !0 === s.allowSlideNext && (e.allowSlideNext = !e.isLocked), !0 === s.allowSlidePrev && (e.allowSlidePrev = !e.isLocked), t && t !== e.isLocked && (e.isEnd = !1), t !== e.isLocked && e.emit(e.isLocked ? "lock" : "unlock")
        }
      },
      classes: {
        addClasses: function () {
          const e = this,
            {
              classNames: t,
              params: s,
              rtl: i,
              $el: n,
              device: r,
              support: a
            } = e,
            l = function (e, t) {
              const s = [];
              return e.forEach((e => {
                "object" == typeof e ? Object.keys(e).forEach((i => {
                  e[i] && s.push(t + i)
                })) : "string" == typeof e && s.push(t + e)
              })), s
            }(["initialized", s.direction, {
              "pointer-events": !a.touch
            }, {
              "free-mode": e.params.freeMode && s.freeMode.enabled
            }, {
              autoheight: s.autoHeight
            }, {
              rtl: i
            }, {
              grid: s.grid && s.grid.rows > 1
            }, {
              "grid-column": s.grid && s.grid.rows > 1 && "column" === s.grid.fill
            }, {
              android: r.android
            }, {
              ios: r.ios
            }, {
              "css-mode": s.cssMode
            }, {
              centered: s.cssMode && s.centeredSlides
            }], s.containerModifierClass);
          t.push(...l), n.addClass([...t].join(" ")), e.emitContainerClasses()
        },
        removeClasses: function () {
          const {
            $el: e,
            classNames: t
          } = this;
          e.removeClass(t.join(" ")), this.emitContainerClasses()
        }
      },
      images: {
        loadImage: function (e, t, s, i, n, a) {
          const l = r();
          let o;

          function c() {
            a && a()
          }
          d(e).parent("picture")[0] || e.complete && n || !t ? c() : (o = new l.Image, o.onload = c, o.onerror = c, i && (o.sizes = i), s && (o.srcset = s), t && (o.src = t))
        },
        preloadImages: function () {
          const e = this;

          function t() {
            null == e || !e || e.destroyed || (void 0 !== e.imagesLoaded && (e.imagesLoaded += 1), e.imagesLoaded === e.imagesToLoad.length && (e.params.updateOnImagesReady && e.update(), e.emit("imagesReady")))
          }
          e.imagesToLoad = e.$el.find("img");
          for (let s = 0; s < e.imagesToLoad.length; s += 1) {
            const i = e.imagesToLoad[s];
            e.loadImage(i, i.currentSrc || i.getAttribute("src"), i.srcset || i.getAttribute("srcset"), i.sizes || i.getAttribute("sizes"), !0, t)
          }
        }
      }
    },
    F = {};
  class V {
    constructor() {
      let e, t;
      for (var s = arguments.length, i = new Array(s), n = 0; n < s; n++) i[n] = arguments[n];
      if (1 === i.length && i[0].constructor && "Object" === Object.prototype.toString.call(i[0]).slice(8, -1) ? t = i[0] : [e, t] = i, t || (t = {}), t = m({}, t), e && !t.el && (t.el = e), t.el && d(t.el).length > 1) {
        const e = [];
        return d(t.el).each((s => {
          const i = m({}, t, {
            el: s
          });
          e.push(new V(i))
        })), e
      }
      const a = this;
      var l;
      a.__swiper__ = !0, a.support = T(), a.device = (void 0 === (l = {
        userAgent: t.userAgent
      }) && (l = {}), b || (b = function (e) {
        let {
          userAgent: t
        } = void 0 === e ? {} : e;
        const s = T(),
          i = r(),
          n = i.navigator.platform,
          a = t || i.navigator.userAgent,
          l = {
            ios: !1,
            android: !1
          },
          o = i.screen.width,
          d = i.screen.height,
          c = a.match(/(Android);?[\s\/]+([\d.]+)?/);
        let p = a.match(/(iPad).*OS\s([\d_]+)/);
        const u = a.match(/(iPod)(.*OS\s([\d_]+))?/),
          h = !p && a.match(/(iPhone\sOS|iOS)\s([\d_]+)/),
          f = "Win32" === n;
        let m = "MacIntel" === n;
        return !p && m && s.touch && ["1024x1366", "1366x1024", "834x1194", "1194x834", "834x1112", "1112x834", "768x1024", "1024x768", "820x1180", "1180x820", "810x1080", "1080x810"].indexOf(`${o}x${d}`) >= 0 && (p = a.match(/(Version)\/([\d.]+)/), p || (p = [0, 1, "13_0_0"]), m = !1), c && !f && (l.os = "android", l.android = !0), (p || h || u) && (l.os = "ios", l.ios = !0), l
      }(l)), b), a.browser = (C || (C = function () {
        const e = r();
        return {
          isSafari: function () {
            const t = e.navigator.userAgent.toLowerCase();
            return t.indexOf("safari") >= 0 && t.indexOf("chrome") < 0 && t.indexOf("android") < 0
          }(),
          isWebView: /(iPhone|iPod|iPad).*AppleWebKit(?!.*Safari)/i.test(e.navigator.userAgent)
        }
      }()), C), a.eventsListeners = {}, a.eventsAnyListeners = [], a.modules = [...a.__modules__], t.modules && Array.isArray(t.modules) && a.modules.push(...t.modules);
      const o = {};
      a.modules.forEach((e => {
        e({
          swiper: a,
          extendParams: j(t, o),
          on: a.on.bind(a),
          once: a.once.bind(a),
          off: a.off.bind(a),
          emit: a.emit.bind(a)
        })
      }));
      const c = m({}, _, o);
      return a.params = m({}, c, F, t), a.originalParams = m({}, a.params), a.passedParams = m({}, t), a.params && a.params.on && Object.keys(a.params.on).forEach((e => {
        a.on(e, a.params.on[e])
      })), a.params && a.params.onAny && a.onAny(a.params.onAny), a.$ = d, Object.assign(a, {
        enabled: a.params.enabled,
        el: e,
        classNames: [],
        slides: d(),
        slidesGrid: [],
        snapGrid: [],
        slidesSizesGrid: [],
        isHorizontal: () => "horizontal" === a.params.direction,
        isVertical: () => "vertical" === a.params.direction,
        activeIndex: 0,
        realIndex: 0,
        isBeginning: !0,
        isEnd: !1,
        translate: 0,
        previousTranslate: 0,
        progress: 0,
        velocity: 0,
        animating: !1,
        allowSlideNext: a.params.allowSlideNext,
        allowSlidePrev: a.params.allowSlidePrev,
        touchEvents: function () {
          const e = ["touchstart", "touchmove", "touchend", "touchcancel"],
            t = ["pointerdown", "pointermove", "pointerup"];
          return a.touchEventsTouch = {
            start: e[0],
            move: e[1],
            end: e[2],
            cancel: e[3]
          }, a.touchEventsDesktop = {
            start: t[0],
            move: t[1],
            end: t[2]
          }, a.support.touch || !a.params.simulateTouch ? a.touchEventsTouch : a.touchEventsDesktop
        }(),
        touchEventsData: {
          isTouched: void 0,
          isMoved: void 0,
          allowTouchCallbacks: void 0,
          touchStartTime: void 0,
          isScrolling: void 0,
          currentTranslate: void 0,
          startTranslate: void 0,
          allowThresholdMove: void 0,
          focusableElements: a.params.focusableElements,
          lastClickTime: u(),
          clickTimeout: void 0,
          velocities: [],
          allowMomentumBounce: void 0,
          isTouchEvent: void 0,
          startMoving: void 0
        },
        allowClick: !0,
        allowTouchMove: a.params.allowTouchMove,
        touches: {
          startX: 0,
          startY: 0,
          currentX: 0,
          currentY: 0,
          diff: 0
        },
        imagesToLoad: [],
        imagesLoaded: 0
      }), a.emit("_swiper"), a.params.init && a.init(), a
    }
    enable() {
      const e = this;
      e.enabled || (e.enabled = !0, e.params.grabCursor && e.setGrabCursor(), e.emit("enable"))
    }
    disable() {
      const e = this;
      !e.enabled || (e.enabled = !1, e.params.grabCursor && e.unsetGrabCursor(), e.emit("disable"))
    }
    setProgress(e, t) {
      const s = this;
      e = Math.min(Math.max(e, 0), 1);
      const i = s.minTranslate(),
        n = (s.maxTranslate() - i) * e + i;
      s.translateTo(n, void 0 === t ? 0 : t), s.updateActiveIndex(), s.updateSlidesClasses()
    }
    emitContainerClasses() {
      const e = this;
      if (!e.params._emitClasses || !e.el) return;
      const t = e.el.className.split(" ").filter((t => 0 === t.indexOf("swiper") || 0 === t.indexOf(e.params.containerModifierClass)));
      e.emit("_containerClasses", t.join(" "))
    }
    getSlideClasses(e) {
      const t = this;
      return e.className.split(" ").filter((e => 0 === e.indexOf("swiper-slide") || 0 === e.indexOf(t.params.slideClass))).join(" ")
    }
    emitSlidesClasses() {
      const e = this;
      if (!e.params._emitClasses || !e.el) return;
      const t = [];
      e.slides.each((s => {
        const i = e.getSlideClasses(s);
        t.push({
          slideEl: s,
          classNames: i
        }), e.emit("_slideClass", s, i)
      })), e.emit("_slideClasses", t)
    }
    slidesPerViewDynamic(e, t) {
      void 0 === e && (e = "current"), void 0 === t && (t = !1);
      const {
        params: s,
        slides: i,
        slidesGrid: n,
        slidesSizesGrid: r,
        size: a,
        activeIndex: l
      } = this;
      let o = 1;
      if (s.centeredSlides) {
        let e, t = i[l].swiperSlideSize;
        for (let s = l + 1; s < i.length; s += 1) i[s] && !e && (t += i[s].swiperSlideSize, o += 1, t > a && (e = !0));
        for (let s = l - 1; s >= 0; s -= 1) i[s] && !e && (t += i[s].swiperSlideSize, o += 1, t > a && (e = !0))
      } else if ("current" === e)
        for (let e = l + 1; e < i.length; e += 1)(t ? n[e] + r[e] - n[l] < a : n[e] - n[l] < a) && (o += 1);
      else
        for (let e = l - 1; e >= 0; e -= 1) n[l] - n[e] < a && (o += 1);
      return o
    }
    update() {
      const e = this;
      if (!e || e.destroyed) return;
      const {
        snapGrid: t,
        params: s
      } = e;

      function i() {
        const t = e.rtlTranslate ? -1 * e.translate : e.translate,
          s = Math.min(Math.max(t, e.maxTranslate()), e.minTranslate());
        e.setTranslate(s), e.updateActiveIndex(), e.updateSlidesClasses()
      }
      let n;
      s.breakpoints && e.setBreakpoint(), e.updateSize(), e.updateSlides(), e.updateProgress(), e.updateSlidesClasses(), e.params.freeMode && e.params.freeMode.enabled ? (i(), e.params.autoHeight && e.updateAutoHeight()) : (n = ("auto" === e.params.slidesPerView || e.params.slidesPerView > 1) && e.isEnd && !e.params.centeredSlides ? e.slideTo(e.slides.length - 1, 0, !1, !0) : e.slideTo(e.activeIndex, 0, !1, !0), n || i()), s.watchOverflow && t !== e.snapGrid && e.checkOverflow(), e.emit("update")
    }
    changeDirection(e, t) {
      void 0 === t && (t = !0);
      const s = this,
        i = s.params.direction;
      return e || (e = "horizontal" === i ? "vertical" : "horizontal"), e === i || "horizontal" !== e && "vertical" !== e || (s.$el.removeClass(`${s.params.containerModifierClass}${i}`).addClass(`${s.params.containerModifierClass}${e}`), s.emitContainerClasses(), s.params.direction = e, s.slides.each((t => {
        "vertical" === e ? t.style.width = "" : t.style.height = ""
      })), s.emit("changeDirection"), t && s.update()), s
    }
    mount(e) {
      const t = this;
      if (t.mounted) return !0;
      const s = d(e || t.params.el);
      if (!(e = s[0])) return !1;
      e.swiper = t;
      const n = () => `.${(t.params.wrapperClass||"").trim().split(" ").join(".")}`;
      let r = (() => {
        if (e && e.shadowRoot && e.shadowRoot.querySelector) {
          const t = d(e.shadowRoot.querySelector(n()));
          return t.children = e => s.children(e), t
        }
        return s.children(n())
      })();
      if (0 === r.length && t.params.createElements) {
        const e = i().createElement("div");
        r = d(e), e.className = t.params.wrapperClass, s.append(e), s.children(`.${t.params.slideClass}`).each((e => {
          r.append(e)
        }))
      }
      return Object.assign(t, {
        $el: s,
        el: e,
        $wrapperEl: r,
        wrapperEl: r[0],
        mounted: !0,
        rtl: "rtl" === e.dir.toLowerCase() || "rtl" === s.css("direction"),
        rtlTranslate: "horizontal" === t.params.direction && ("rtl" === e.dir.toLowerCase() || "rtl" === s.css("direction")),
        wrongRTL: "-webkit-box" === r.css("display")
      }), !0
    }
    init(e) {
      const t = this;
      return t.initialized || !1 === t.mount(e) || (t.emit("beforeInit"), t.params.breakpoints && t.setBreakpoint(), t.addClasses(), t.params.loop && t.loopCreate(), t.updateSize(), t.updateSlides(), t.params.watchOverflow && t.checkOverflow(), t.params.grabCursor && t.enabled && t.setGrabCursor(), t.params.preloadImages && t.preloadImages(), t.params.loop ? t.slideTo(t.params.initialSlide + t.loopedSlides, 0, t.params.runCallbacksOnInit, !1, !0) : t.slideTo(t.params.initialSlide, 0, t.params.runCallbacksOnInit, !1, !0), t.attachEvents(), t.initialized = !0, t.emit("init"), t.emit("afterInit")), t
    }
    destroy(e, t) {
      void 0 === e && (e = !0), void 0 === t && (t = !0);
      const s = this,
        {
          params: i,
          $el: n,
          $wrapperEl: r,
          slides: a
        } = s;
      return void 0 === s.params || s.destroyed || (s.emit("beforeDestroy"), s.initialized = !1, s.detachEvents(), i.loop && s.loopDestroy(), t && (s.removeClasses(), n.removeAttr("style"), r.removeAttr("style"), a && a.length && a.removeClass([i.slideVisibleClass, i.slideActiveClass, i.slideNextClass, i.slidePrevClass].join(" ")).removeAttr("style").removeAttr("data-swiper-slide-index")), s.emit("destroy"), Object.keys(s.eventsListeners).forEach((e => {
        s.off(e)
      })), !1 !== e && (s.$el[0].swiper = null, function (e) {
        const t = e;
        Object.keys(t).forEach((e => {
          try {
            t[e] = null
          } catch {}
          try {
            delete t[e]
          } catch {}
        }))
      }(s)), s.destroyed = !0), null
    }
    static extendDefaults(e) {
      m(F, e)
    }
    static get extendedDefaults() {
      return F
    }
    static get defaults() {
      return _
    }
    static installModule(e) {
      V.prototype.__modules__ || (V.prototype.__modules__ = []);
      const t = V.prototype.__modules__;
      "function" == typeof e && t.indexOf(e) < 0 && t.push(e)
    }
    static use(e) {
      return Array.isArray(e) ? (e.forEach((e => V.installModule(e))), V) : (V.installModule(e), V)
    }
  }

  function q(e, t, s, n) {
    const r = i();
    return e.params.createElements && Object.keys(n).forEach((i => {
      if (!s[i] && !0 === s.auto) {
        let a = e.$el.children(`.${n[i]}`)[0];
        a || (a = r.createElement("div"), a.className = n[i], e.$el.append(a)), s[i] = a, t[i] = a
      }
    })), s
  }

  function W(e) {
    let {
      swiper: t,
      extendParams: s,
      on: i,
      emit: n
    } = e;

    function r(e) {
      let s;
      return e && (s = d(e), t.params.uniqueNavElements && "string" == typeof e && s.length > 1 && 1 === t.$el.find(e).length && (s = t.$el.find(e))), s
    }

    function a(e, s) {
      const i = t.params.navigation;
      e && e.length > 0 && (e[s ? "addClass" : "removeClass"](i.disabledClass), e[0] && "BUTTON" === e[0].tagName && (e[0].disabled = s), t.params.watchOverflow && t.enabled && e[t.isLocked ? "addClass" : "removeClass"](i.lockClass))
    }

    function l() {
      if (t.params.loop) return;
      const {
        $nextEl: e,
        $prevEl: s
      } = t.navigation;
      a(s, t.isBeginning && !t.params.rewind), a(e, t.isEnd && !t.params.rewind)
    }

    function o(e) {
      e.preventDefault(), (!t.isBeginning || t.params.loop || t.params.rewind) && t.slidePrev()
    }

    function c(e) {
      e.preventDefault(), (!t.isEnd || t.params.loop || t.params.rewind) && t.slideNext()
    }

    function p() {
      const e = t.params.navigation;
      if (t.params.navigation = q(t, t.originalParams.navigation, t.params.navigation, {
          nextEl: "swiper-button-next",
          prevEl: "swiper-button-prev"
        }), !e.nextEl && !e.prevEl) return;
      const s = r(e.nextEl),
        i = r(e.prevEl);
      s && s.length > 0 && s.on("click", c), i && i.length > 0 && i.on("click", o), Object.assign(t.navigation, {
        $nextEl: s,
        nextEl: s && s[0],
        $prevEl: i,
        prevEl: i && i[0]
      }), t.enabled || (s && s.addClass(e.lockClass), i && i.addClass(e.lockClass))
    }

    function u() {
      const {
        $nextEl: e,
        $prevEl: s
      } = t.navigation;
      e && e.length && (e.off("click", c), e.removeClass(t.params.navigation.disabledClass)), s && s.length && (s.off("click", o), s.removeClass(t.params.navigation.disabledClass))
    }
    s({
      navigation: {
        nextEl: null,
        prevEl: null,
        hideOnClick: !1,
        disabledClass: "swiper-button-disabled",
        hiddenClass: "swiper-button-hidden",
        lockClass: "swiper-button-lock"
      }
    }), t.navigation = {
      nextEl: null,
      $nextEl: null,
      prevEl: null,
      $prevEl: null
    }, i("init", (() => {
      p(), l()
    })), i("toEdge fromEdge lock unlock", (() => {
      l()
    })), i("destroy", (() => {
      u()
    })), i("enable disable", (() => {
      const {
        $nextEl: e,
        $prevEl: s
      } = t.navigation;
      e && e[t.enabled ? "removeClass" : "addClass"](t.params.navigation.lockClass), s && s[t.enabled ? "removeClass" : "addClass"](t.params.navigation.lockClass)
    })), i("click", ((e, s) => {
      const {
        $nextEl: i,
        $prevEl: r
      } = t.navigation, a = s.target;
      if (t.params.navigation.hideOnClick && !d(a).is(r) && !d(a).is(i)) {
        if (t.pagination && t.params.pagination && t.params.pagination.clickable && (t.pagination.el === a || t.pagination.el.contains(a))) return;
        let e;
        i ? e = i.hasClass(t.params.navigation.hiddenClass) : r && (e = r.hasClass(t.params.navigation.hiddenClass)), n(!0 === e ? "navigationShow" : "navigationHide"), i && i.toggleClass(t.params.navigation.hiddenClass), r && r.toggleClass(t.params.navigation.hiddenClass)
      }
    })), Object.assign(t.navigation, {
      update: l,
      init: p,
      destroy: u
    })
  }

  function R(e) {
    return void 0 === e && (e = ""), `.${e.trim().replace(/([\.:!\/])/g,"\\$1").replace(/ /g,".")}`
  }

  function X(e) {
    let {
      swiper: t,
      extendParams: s,
      on: i,
      emit: n
    } = e;
    const r = "swiper-pagination";
    s({
      pagination: {
        el: null,
        bulletElement: "span",
        clickable: !1,
        hideOnClick: !1,
        renderBullet: null,
        renderProgressbar: null,
        renderFraction: null,
        renderCustom: null,
        progressbarOpposite: !1,
        type: "bullets",
        dynamicBullets: !1,
        dynamicMainBullets: 1,
        formatFractionCurrent: e => e,
        formatFractionTotal: e => e,
        bulletClass: `${r}-bullet`,
        bulletActiveClass: `${r}-bullet-active`,
        modifierClass: `${r}-`,
        currentClass: `${r}-current`,
        totalClass: `${r}-total`,
        hiddenClass: `${r}-hidden`,
        progressbarFillClass: `${r}-progressbar-fill`,
        progressbarOppositeClass: `${r}-progressbar-opposite`,
        clickableClass: `${r}-clickable`,
        lockClass: `${r}-lock`,
        horizontalClass: `${r}-horizontal`,
        verticalClass: `${r}-vertical`
      }
    }), t.pagination = {
      el: null,
      $el: null,
      bullets: []
    };
    let a, l = 0;

    function o() {
      return !t.params.pagination.el || !t.pagination.el || !t.pagination.$el || 0 === t.pagination.$el.length
    }

    function c(e, s) {
      const {
        bulletActiveClass: i
      } = t.params.pagination;
      e[s]().addClass(`${i}-${s}`)[s]().addClass(`${i}-${s}-${s}`)
    }

    function p() {
      const e = t.rtl,
        s = t.params.pagination;
      if (o()) return;
      const i = t.virtual && t.params.virtual.enabled ? t.virtual.slides.length : t.slides.length,
        r = t.pagination.$el;
      let p;
      const u = t.params.loop ? Math.ceil((i - 2 * t.loopedSlides) / t.params.slidesPerGroup) : t.snapGrid.length;
      if (t.params.loop ? (p = Math.ceil((t.activeIndex - t.loopedSlides) / t.params.slidesPerGroup), p > i - 1 - 2 * t.loopedSlides && (p -= i - 2 * t.loopedSlides), p > u - 1 && (p -= u), p < 0 && "bullets" !== t.params.paginationType && (p = u + p)) : p = void 0 !== t.snapIndex ? t.snapIndex : t.activeIndex || 0, "bullets" === s.type && t.pagination.bullets && t.pagination.bullets.length > 0) {
        const i = t.pagination.bullets;
        let n, o, u;
        if (s.dynamicBullets && (a = i.eq(0)[t.isHorizontal() ? "outerWidth" : "outerHeight"](!0), r.css(t.isHorizontal() ? "width" : "height", a * (s.dynamicMainBullets + 4) + "px"), s.dynamicMainBullets > 1 && void 0 !== t.previousIndex && (l += p - (t.previousIndex - t.loopedSlides || 0), l > s.dynamicMainBullets - 1 ? l = s.dynamicMainBullets - 1 : l < 0 && (l = 0)), n = Math.max(p - l, 0), o = n + (Math.min(i.length, s.dynamicMainBullets) - 1), u = (o + n) / 2), i.removeClass(["", "-next", "-next-next", "-prev", "-prev-prev", "-main"].map((e => `${s.bulletActiveClass}${e}`)).join(" ")), r.length > 1) i.each((e => {
          const t = d(e),
            i = t.index();
          i === p && t.addClass(s.bulletActiveClass), s.dynamicBullets && (i >= n && i <= o && t.addClass(`${s.bulletActiveClass}-main`), i === n && c(t, "prev"), i === o && c(t, "next"))
        }));
        else {
          const e = i.eq(p),
            r = e.index();
          if (e.addClass(s.bulletActiveClass), s.dynamicBullets) {
            const e = i.eq(n),
              a = i.eq(o);
            for (let e = n; e <= o; e += 1) i.eq(e).addClass(`${s.bulletActiveClass}-main`);
            if (t.params.loop)
              if (r >= i.length) {
                for (let e = s.dynamicMainBullets; e >= 0; e -= 1) i.eq(i.length - e).addClass(`${s.bulletActiveClass}-main`);
                i.eq(i.length - s.dynamicMainBullets - 1).addClass(`${s.bulletActiveClass}-prev`)
              } else c(e, "prev"), c(a, "next");
            else c(e, "prev"), c(a, "next")
          }
        }
        if (s.dynamicBullets) {
          const n = Math.min(i.length, s.dynamicMainBullets + 4),
            r = (a * n - a) / 2 - u * a,
            l = e ? "right" : "left";
          i.css(t.isHorizontal() ? l : "top", `${r}px`)
        }
      }
      if ("fraction" === s.type && (r.find(R(s.currentClass)).text(s.formatFractionCurrent(p + 1)), r.find(R(s.totalClass)).text(s.formatFractionTotal(u))), "progressbar" === s.type) {
        let e;
        e = s.progressbarOpposite ? t.isHorizontal() ? "vertical" : "horizontal" : t.isHorizontal() ? "horizontal" : "vertical";
        const i = (p + 1) / u;
        let n = 1,
          a = 1;
        "horizontal" === e ? n = i : a = i, r.find(R(s.progressbarFillClass)).transform(`translate3d(0,0,0) scaleX(${n}) scaleY(${a})`).transition(t.params.speed)
      }
      "custom" === s.type && s.renderCustom ? (r.html(s.renderCustom(t, p + 1, u)), n("paginationRender", r[0])) : n("paginationUpdate", r[0]), t.params.watchOverflow && t.enabled && r[t.isLocked ? "addClass" : "removeClass"](s.lockClass)
    }

    function u() {
      const e = t.params.pagination;
      if (o()) return;
      const s = t.virtual && t.params.virtual.enabled ? t.virtual.slides.length : t.slides.length,
        i = t.pagination.$el;
      let r = "";
      if ("bullets" === e.type) {
        let n = t.params.loop ? Math.ceil((s - 2 * t.loopedSlides) / t.params.slidesPerGroup) : t.snapGrid.length;
        t.params.freeMode && t.params.freeMode.enabled && !t.params.loop && n > s && (n = s);
        for (let s = 0; s < n; s += 1) e.renderBullet ? r += e.renderBullet.call(t, s, e.bulletClass) : r += `<${e.bulletElement} class="${e.bulletClass}"></${e.bulletElement}>`;
        i.html(r), t.pagination.bullets = i.find(R(e.bulletClass))
      }
      "fraction" === e.type && (r = e.renderFraction ? e.renderFraction.call(t, e.currentClass, e.totalClass) : `<span class="${e.currentClass}"></span> / <span class="${e.totalClass}"></span>`, i.html(r)), "progressbar" === e.type && (r = e.renderProgressbar ? e.renderProgressbar.call(t, e.progressbarFillClass) : `<span class="${e.progressbarFillClass}"></span>`, i.html(r)), "custom" !== e.type && n("paginationRender", t.pagination.$el[0])
    }

    function h() {
      t.params.pagination = q(t, t.originalParams.pagination, t.params.pagination, {
        el: "swiper-pagination"
      });
      const e = t.params.pagination;
      if (!e.el) return;
      let s = d(e.el);
      0 !== s.length && (t.params.uniqueNavElements && "string" == typeof e.el && s.length > 1 && (s = t.$el.find(e.el), s.length > 1 && (s = s.filter((e => d(e).parents(".swiper")[0] === t.el)))), "bullets" === e.type && e.clickable && s.addClass(e.clickableClass), s.addClass(e.modifierClass + e.type), s.addClass(e.modifierClass + t.params.direction), "bullets" === e.type && e.dynamicBullets && (s.addClass(`${e.modifierClass}${e.type}-dynamic`), l = 0, e.dynamicMainBullets < 1 && (e.dynamicMainBullets = 1)), "progressbar" === e.type && e.progressbarOpposite && s.addClass(e.progressbarOppositeClass), e.clickable && s.on("click", R(e.bulletClass), (function (e) {
        e.preventDefault();
        let s = d(this).index() * t.params.slidesPerGroup;
        t.params.loop && (s += t.loopedSlides), t.slideTo(s)
      })), Object.assign(t.pagination, {
        $el: s,
        el: s[0]
      }), t.enabled || s.addClass(e.lockClass))
    }

    function f() {
      const e = t.params.pagination;
      if (o()) return;
      const s = t.pagination.$el;
      s.removeClass(e.hiddenClass), s.removeClass(e.modifierClass + e.type), s.removeClass(e.modifierClass + t.params.direction), t.pagination.bullets && t.pagination.bullets.removeClass && t.pagination.bullets.removeClass(e.bulletActiveClass), e.clickable && s.off("click", R(e.bulletClass))
    }
    i("init", (() => {
      h(), u(), p()
    })), i("activeIndexChange", (() => {
      (t.params.loop || void 0 === t.snapIndex) && p()
    })), i("snapIndexChange", (() => {
      t.params.loop || p()
    })), i("slidesLengthChange", (() => {
      t.params.loop && (u(), p())
    })), i("snapGridLengthChange", (() => {
      t.params.loop || (u(), p())
    })), i("destroy", (() => {
      f()
    })), i("enable disable", (() => {
      const {
        $el: e
      } = t.pagination;
      e && e[t.enabled ? "removeClass" : "addClass"](t.params.pagination.lockClass)
    })), i("lock unlock", (() => {
      p()
    })), i("click", ((e, s) => {
      const i = s.target,
        {
          $el: r
        } = t.pagination;
      if (t.params.pagination.el && t.params.pagination.hideOnClick && r.length > 0 && !d(i).hasClass(t.params.pagination.bulletClass)) {
        if (t.navigation && (t.navigation.nextEl && i === t.navigation.nextEl || t.navigation.prevEl && i === t.navigation.prevEl)) return;
        const e = r.hasClass(t.params.pagination.hiddenClass);
        n(!0 === e ? "paginationShow" : "paginationHide"), r.toggleClass(t.params.pagination.hiddenClass)
      }
    })), Object.assign(t.pagination, {
      render: u,
      update: p,
      init: h,
      destroy: f
    })
  }

  function Y(e, t) {
    return e.transformEl ? t.find(e.transformEl).css({
      "backface-visibility": "hidden",
      "-webkit-backface-visibility": "hidden"
    }) : t
  }

  function U(e, t, s) {
    const i = "swiper-slide-shadow" + (s ? `-${s}` : ""),
      n = e.transformEl ? t.find(e.transformEl) : t;
    let r = n.children(`.${i}`);
    return r.length || (r = d(`<div class="swiper-slide-shadow${s?`-${s}`:""}"></div>`), n.append(r)), r
  }

  function K(e) {
    let {
      swiper: t,
      extendParams: s,
      on: i
    } = e;
    s({
      creativeEffect: {
        transformEl: null,
        limitProgress: 1,
        shadowPerProgress: !1,
        progressMultiplier: 1,
        perspective: !0,
        prev: {
          translate: [0, 0, 0],
          rotate: [0, 0, 0],
          opacity: 1,
          scale: 1
        },
        next: {
          translate: [0, 0, 0],
          rotate: [0, 0, 0],
          opacity: 1,
          scale: 1
        }
      }
    });
    const n = e => "string" == typeof e ? e : `${e}px`;
    ! function (e) {
      const {
        effect: t,
        swiper: s,
        on: i,
        setTranslate: n,
        setTransition: r,
        overwriteParams: a,
        perspective: l
      } = e;
      i("beforeInit", (() => {
        if (s.params.effect !== t) return;
        s.classNames.push(`${s.params.containerModifierClass}${t}`), l && l() && s.classNames.push(`${s.params.containerModifierClass}3d`);
        const e = a ? a() : {};
        Object.assign(s.params, e), Object.assign(s.originalParams, e)
      })), i("setTranslate", (() => {
        s.params.effect === t && n()
      })), i("setTransition", ((e, i) => {
        s.params.effect === t && r(i)
      }))
    }({
      effect: "creative",
      swiper: t,
      on: i,
      setTranslate: () => {
        const {
          slides: e,
          $wrapperEl: s,
          slidesSizesGrid: i
        } = t, r = t.params.creativeEffect, {
          progressMultiplier: a
        } = r, l = t.params.centeredSlides;
        if (l) {
          const e = i[0] / 2 - t.params.slidesOffsetBefore || 0;
          s.transform(`translateX(calc(50% - ${e}px))`)
        }
        for (let s = 0; s < e.length; s += 1) {
          const i = e.eq(s),
            o = i[0].progress,
            d = Math.min(Math.max(i[0].progress, -r.limitProgress), r.limitProgress);
          let c = d;
          l || (c = Math.min(Math.max(i[0].originalProgress, -r.limitProgress), r.limitProgress));
          const p = i[0].swiperSlideOffset,
            u = [t.params.cssMode ? -p - t.translate : -p, 0, 0],
            h = [0, 0, 0];
          let f = !1;
          t.isHorizontal() || (u[1] = u[0], u[0] = 0);
          let m = {
            translate: [0, 0, 0],
            rotate: [0, 0, 0],
            scale: 1,
            opacity: 1
          };
          d < 0 ? (m = r.next, f = !0) : d > 0 && (m = r.prev, f = !0), u.forEach(((e, t) => {
            u[t] = `calc(${e}px + (${n(m.translate[t])} * ${Math.abs(d*a)}))`
          })), h.forEach(((e, t) => {
            h[t] = m.rotate[t] * Math.abs(d * a)
          })), i[0].style.zIndex = -Math.abs(Math.round(o)) + e.length;
          const g = u.join(", "),
            v = `rotateX(${h[0]}deg) rotateY(${h[1]}deg) rotateZ(${h[2]}deg)`,
            w = c < 0 ? `scale(${1+(1-m.scale)*c*a})` : `scale(${1-(1-m.scale)*c*a})`,
            b = c < 0 ? 1 + (1 - m.opacity) * c * a : 1 - (1 - m.opacity) * c * a,
            C = `translate3d(${g}) ${v} ${w}`;
          if (f && m.shadow || !f) {
            let e = i.children(".swiper-slide-shadow");
            if (0 === e.length && m.shadow && (e = U(r, i)), e.length) {
              const t = r.shadowPerProgress ? d * (1 / r.limitProgress) : d;
              e[0].style.opacity = Math.min(Math.max(Math.abs(t), 0), 1)
            }
          }
          const T = Y(r, i);
          T.transform(C).css({
            opacity: b
          }), m.origin && T.css("transform-origin", m.origin)
        }
      },
      setTransition: e => {
        const {
          transformEl: s
        } = t.params.creativeEffect;
        (s ? t.slides.find(s) : t.slides).transition(e).find(".swiper-slide-shadow").transition(e),
          function (e) {
            let {
              swiper: t,
              duration: s,
              transformEl: i,
              allSlides: n
            } = e;
            const {
              slides: r,
              activeIndex: a,
              $wrapperEl: l
            } = t;
            if (t.params.virtualTranslate && 0 !== s) {
              let e, s = !1;
              e = n ? i ? r.find(i) : r : i ? r.eq(a).find(i) : r.eq(a), e.transitionEnd((() => {
                if (s || !t || t.destroyed) return;
                s = !0, t.animating = !1;
                const e = ["webkitTransitionEnd", "transitionend"];
                for (let t = 0; t < e.length; t += 1) l.trigger(e[t])
              }))
            }
          }({
            swiper: t,
            duration: e,
            transformEl: s,
            allSlides: !0
          })
      },
      perspective: () => t.params.creativeEffect.perspective,
      overwriteParams: () => ({
        watchSlidesProgress: !0,
        virtualTranslate: !t.params.cssMode
      })
    })
  }
  Object.keys(H).forEach((e => {
    Object.keys(H[e]).forEach((t => {
      V.prototype[t] = H[e][t]
    }))
  })), V.use([function (e) {
    let {
      swiper: t,
      on: s,
      emit: i
    } = e;
    const n = r();
    let a = null,
      l = null;
    const o = () => {
        !t || t.destroyed || !t.initialized || (i("beforeResize"), i("resize"))
      },
      d = () => {
        !t || t.destroyed || !t.initialized || i("orientationchange")
      };
    s("init", (() => {
      t.params.resizeObserver && void 0 !== n.ResizeObserver ? !t || t.destroyed || !t.initialized || (a = new ResizeObserver((e => {
        l = n.requestAnimationFrame((() => {
          const {
            width: s,
            height: i
          } = t;
          let n = s,
            r = i;
          e.forEach((e => {
            let {
              contentBoxSize: s,
              contentRect: i,
              target: a
            } = e;
            a && a !== t.el || (n = i ? i.width : (s[0] || s).inlineSize, r = i ? i.height : (s[0] || s).blockSize)
          })), (n !== s || r !== i) && o()
        }))
      })), a.observe(t.el)) : (n.addEventListener("resize", o), n.addEventListener("orientationchange", d))
    })), s("destroy", (() => {
      l && n.cancelAnimationFrame(l), a && a.unobserve && t.el && (a.unobserve(t.el), a = null), n.removeEventListener("resize", o), n.removeEventListener("orientationchange", d)
    }))
  }, function (e) {
    let {
      swiper: t,
      extendParams: s,
      on: i,
      emit: n
    } = e;
    const a = [],
      l = r(),
      o = function (e, t) {
        void 0 === t && (t = {});
        const s = new(l.MutationObserver || l.WebkitMutationObserver)((e => {
          if (1 === e.length) return void n("observerUpdate", e[0]);
          const t = function () {
            n("observerUpdate", e[0])
          };
          l.requestAnimationFrame ? l.requestAnimationFrame(t) : l.setTimeout(t, 0)
        }));
        s.observe(e, {
          attributes: void 0 === t.attributes || t.attributes,
          childList: void 0 === t.childList || t.childList,
          characterData: void 0 === t.characterData || t.characterData
        }), a.push(s)
      };
    s({
      observer: !1,
      observeParents: !1,
      observeSlideChildren: !1
    }), i("init", (() => {
      if (t.params.observer) {
        if (t.params.observeParents) {
          const e = t.$el.parents();
          for (let t = 0; t < e.length; t += 1) o(e[t])
        }
        o(t.$el[0], {
          childList: t.params.observeSlideChildren
        }), o(t.$wrapperEl[0], {
          attributes: !1
        })
      }
    })), i("destroy", (() => {
      a.forEach((e => {
        e.disconnect()
      })), a.splice(0, a.length)
    }))
  }]);
  var Z = Object.defineProperty,
    J = Object.defineProperties,
    Q = Object.getOwnPropertyDescriptors,
    ee = Object.getOwnPropertySymbols,
    te = Object.prototype.hasOwnProperty,
    se = Object.prototype.propertyIsEnumerable,
    ie = (e, t, s) => t in e ? Z(e, t, {
      enumerable: !0,
      configurable: !0,
      writable: !0,
      value: s
    }) : e[t] = s,
    ne = (e, t) => {
      for (var s in t || (t = {})) te.call(t, s) && ie(e, s, t[s]);
      if (ee)
        for (var s of ee(t)) se.call(t, s) && ie(e, s, t[s]);
      return e
    },
    re = (e, t) => J(e, Q(t));
  ! function () {
    const e = document.createElement("link").relList;
    if (!(e && e.supports && e.supports("modulepreload"))) {
      for (const e of document.querySelectorAll('link[rel="modulepreload"]')) t(e);
      new MutationObserver((e => {
        for (const s of e)
          if ("childList" === s.type)
            for (const e of s.addedNodes) "LINK" === e.tagName && "modulepreload" === e.rel && t(e)
      })).observe(document, {
        childList: !0,
        subtree: !0
      })
    }

    function t(e) {
      if (e.ep) return;
      e.ep = !0;
      const t = function (e) {
        const t = {};
        return e.integrity && (t.integrity = e.integrity), e.referrerpolicy && (t.referrerPolicy = e.referrerpolicy), "use-credentials" === e.crossorigin ? t.credentials = "include" : "anonymous" === e.crossorigin ? t.credentials = "omit" : t.credentials = "same-origin", t
      }(e);
      fetch(e.href, t)
    }
  }();
  const ae = document.querySelectorAll(".swiper-carousel");
  let le = 0;
  ae.forEach((function (e) {
    e.classList.add(`swiper-carousel-${le}`);
    const t = e.getAttribute("data-column"),
      s = "true" === e.getAttribute("data-loop"),
      i = "false" === e.getAttribute("data-drag");
    ! function (e, t = {}) {
      const s = e.querySelector(".swiper");
      let i = 0,
        n = !1;
      const r = new V(s, re(ne({
        effect: "creative",
        speed: 720,
        followFinger: !1
      }, t), {
        modules: [K, ...t.modules || []],
        creativeEffect: {
          limitProgress: 100,
          prev: {
            translate: ["-100%", 0, 0]
          },
          next: {
            translate: ["100%", 0, 0]
          }
        },
        on: re(ne({}, t.on || {}), {
          touchStart(...e) {
            n = !0, t.on && t.on.touchStart && t.on.touchStart(...e)
          },
          touchEnd(...e) {
            n = !1, t.on && t.on.touchStart && t.on.touchEnd(...e)
          },
          progress(e, s) {
            if (n) return;
            t.on && t.on.progress && t.on.progress(e, s);
            const r = e.progress > i ? "next" : "prev";
            i = e.progress;
            const a = e.params.speed / 16,
              l = e.visibleSlidesIndexes,
              o = l[0],
              d = l[l.length - 1],
              c = (e, t) => {
                e.style.transitionDelay = "next" === r && t >= o ? (t - o + 1) * a + "ms" : "prev" === r && t <= d + 1 ? (d - t + 1) * a + "ms" : "0ms"
              };
            e.slides.forEach(((t, s) => {
              e.animating ? (t.style.transitionDelay = "0ms", requestAnimationFrame((() => {
                c(t, s)
              }))) : c(t, s)
            }))
          }
        })
      }));
      r.on("transitionEnd resize touchStart", (() => {
        r.slides.forEach((e => {
          e.style.transitionDelay = "0ms"
        }))
      }))
    }(e, {
      modules: [W, X],
      slidesPerView: 1,
      spaceBetween: 0,
      navigation: {
        prevEl: ".slider-button-prev",
        nextEl: ".slider-button-next"
      },
      pagination: {
        el: `.swiper-carousel-${le} .swiper-pagination`,
        type: "progressbar"
      },
      loop: s,
      breakpoints: {
        320: {
          slidesPerView: 1,
          touchRatio: 1
        },
        480: {
          slidesPerView: 1,
          touchRatio: 1
        },
        768: {
          slidesPerView: 2,
          touchRatio: 1
        },
        992: {
          slidesPerView: 3,
          touchRatio: 1
        },


        1140: {
          slidesPerView: t,
          touchRatio: i ? 0 : 1
        }
      }
    }), le++
  }))
})();
