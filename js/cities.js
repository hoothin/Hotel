(function(d, b, a, e) {
    var c = function(g, f) {
        this.$ele = g,
        this.defaults = {},
        this.listBox = cities,
        this.options = d.extend(true, this.defaults, f || {})
    };
    c.prototype = {
        constructor: c,
        init: function() {
            this.boxHieght = this.$ele.height();
            this.initList();
            this.initNavBar();
            // this.initPrompt();
            // this.bindBarEvent()
        },
        initList: function() {
            var g = "";
            for (let l in this.listBox) {
                var f = this.listBox[l];
                g += "<dl><dt id=" + l + ">" + l + "</dt>";
                for (let i in f) {
                    g += '<dd data-link="' + l + '"><div class="name">' + i + '</div><div class="aside"><div class="orange fz16">'+f[i]+'</div><div class="gray">酒店住宿</div></div></dd>'
                }
                g += "</dl>"
            }
            this.$ele.html("<section>" + g + "</section>")
        },
        initNavBar: function() {
            var g = this.boxHieght / 26;
            var f = '<nav id="navBar">',
            j = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
            for (let i = 0; i < 26; i++) {
                f += '<a href="#' + j[i] + '" >' + j[i] + "</a>"
            }
            f += "</nav>";
            this.$ele.append(f)
        },
        initPrompt: function() {
            this.$ele.append('<span id="prompt"></span>');
            this.p = a.getElementById("prompt")
        },
        bindBarEvent: function() {
            var f = this,
            j = a.querySelector("#navBar"),
            h = null;
            var k = function(o) {
                if (h) {
                    h.classList.remove("active");
                    h = null
                }
                j.classList.add("active");
                var n = o.changedTouches ? o.changedTouches[0] : o;
                h = a.elementFromPoint(n.pageX, n.pageY);
                if (h) {
                    var m = h.innerText;
                    if (m && m.length == 1) {
                        h.classList.add("active");
                        f.p.innerText = m;
                        f.p.classList.add("active");
                        f.scrollTo(m, h)
                    }
                }
                o.preventDefault()
            };
            var g = function(m) {
                f.p.classList.remove("active");
                j.classList.remove("active");
                if (h) {
                    h.classList.remove("active");
                    h = null
                }
            };
            j.addEventListener("touchstart",
            function(m) {
                k(m)
            },
            false);
            j.addEventListener("touchmove",
            function() {
                k(event)
            },
            false);
            a.body.addEventListener("touchend",
            function(m) {
                g(m)
            },
            false);
            a.body.addEventListener("touchcancel",
            function(m) {
                g(m)
            },
            false)
        },
        scrollTo: function(f, h) {
            var g = this;
            if (h && h.href) {
                location.href = h.href;
                return false
            }
        }
    };
    d.fn.myList = function(g) {
        var f = new c(this, g);
        return f.init()
    }
})(Zepto, window, document);