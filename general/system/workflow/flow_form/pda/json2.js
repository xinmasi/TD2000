if (typeof JSON2 !== "object") {
    JSON2 = {}
}
(function() {
    "use strict";
    function f(t) {
        return t < 10 ? "0" + t : t
    }
    if (typeof Date.prototype.toJSON !== "function") {
        Date.prototype.toJSON = function() {
            return isFinite(this.valueOf()) ? this.getUTCFullYear() + "-" + f(this.getUTCMonth() + 1) + "-" + f(this.getUTCDate()) + "T" + f(this.getUTCHours()) + ":" + f(this.getUTCMinutes()) + ":" + f(this.getUTCSeconds()) + "Z" : null 
        }
        ;
        String.prototype.toJSON = Number.prototype.toJSON = Boolean.prototype.toJSON = function() {
            return this.valueOf()
        }
    }
    var cx = /[\u0000\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g, escapable = /[\\\"\x00-\x1f\x7f-\x9f\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g, gap, indent, meta = {
        "\b": "\\b",
        "	": "\\t",
        "\n": "\\n",
        "\f": "\\f",
        "\r": "\\r",
        '"': '\\"',
        "\\": "\\\\"
    }, rep;
    function quote(t) {
        escapable.lastIndex = 0;
        return escapable.test(t) ? '"' + t.replace(escapable, function(t) {
            var e = meta[t];
            return typeof e === "string" ? e : "\\u" + ("0000" + t.charCodeAt(0).toString(16)).slice(-4)
        }) + '"' : '"' + t + '"'
    }
    function str(t, e) {
        var n, r, f, o, u = gap, i, p = e[t];
        if (p && typeof p === "object" && typeof p.toJSON === "function") {
            p = p.toJSON(t)
        }
        if (typeof rep === "function") {
            p = rep.call(e, t, p)
        }
        switch (typeof p) {
        case "string":
            return quote(p);
        case "number":
            return isFinite(p) ? String(p) : "null";
        case "boolean":
        case "null":
            return String(p);
        case "object":
            if (!p) {
                return "null"
            }
            gap += indent;
            i = [];
            if (Object.prototype.toString.apply(p) === "[object Array]") {
                o = p.length;
                for (n = 0; n < o; n += 1) {
                    i[n] = str(n, p) || "null"
                }
                f = i.length === 0 ? "[]" : gap ? "[\n" + gap + i.join(",\n" + gap) + "\n" + u + "]" : "[" + i.join(",") + "]";
                gap = u;
                return f
            }
            if (rep && typeof rep === "object") {
                o = rep.length;
                for (n = 0; n < o; n += 1) {
                    if (typeof rep[n] === "string") {
                        r = rep[n];
                        f = str(r, p);
                        if (f) {
                            i.push(quote(r) + (gap ? ": " : ":") + f)
                        }
                    }
                }
            } else {
                for (r in p) {
                    if (Object.prototype.hasOwnProperty.call(p, r)) {
                        f = str(r, p);
                        if (f) {
                            i.push(quote(r) + (gap ? ": " : ":") + f)
                        }
                    }
                }
            }
            f = i.length === 0 ? "{}" : gap ? "{\n" + gap + i.join(",\n" + gap) + "\n" + u + "}" : "{" + i.join(",") + "}";
            gap = u;
            return f
        }
    }
    if (typeof JSON2.stringify !== "function") {
        JSON2.stringify = function(t, e, n) {
            var r;
            gap = "";
            indent = "";
            if (typeof n === "number") {
                for (r = 0; r < n; r += 1) {
                    indent += " "
                }
            } else if (typeof n === "string") {
                indent = n
            }
            rep = e;
            if (e && typeof e !== "function" && (typeof e !== "object" || typeof e.length !== "number")) {
                throw new Error("JSON.stringify")
            }
            return str("", {
                "": t
            })
        }
    }
    if (typeof JSON2.parse !== "function") {
        JSON2.parse = function(text, reviver) {
            var j;
            function walk(t, e) {
                var n, r, f = t[e];
                if (f && typeof f === "object") {
                    for (n in f) {
                        if (Object.prototype.hasOwnProperty.call(f, n)) {
                            r = walk(f, n);
                            if (r !== undefined) {
                                f[n] = r
                            } else {
                                delete f[n]
                            }
                        }
                    }
                }
                return reviver.call(t, e, f)
            }
            text = String(text);
            cx.lastIndex = 0;
            if (cx.test(text)) {
                text = text.replace(cx, function(t) {
                    return "\\u" + ("0000" + t.charCodeAt(0).toString(16)).slice(-4)
                })
            }
            if (/^[\],:{}\s]*$/.test(text.replace(/\\(?:["\\\/bfnrt]|u[0-9a-fA-F]{4})/g, "@").replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, "]").replace(/(?:^|:|,)(?:\s*\[)+/g, ""))) {
                j = eval("(" + text + ")");
                return typeof reviver === "function" ? walk({
                    "": j
                }, "") : j
            }
            throw new SyntaxError("JSON.parse")
        }
    }
})();
