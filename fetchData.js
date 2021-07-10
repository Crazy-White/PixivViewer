"use strict";

(() => {
    function stringifySearch(q) {
        if (typeof q !== "object")
            throw new Error("incoming parameters must be Object");
        let param = "";
        for (let k in q) {
            let v = q[k];
            if (Array.isArray(v)) {
                for (e of v) {
                    param += `${k}=${e}&`;
                }
            } else {
                param += `${k}=${v}&`;
            }
        }
        param = param.slice(0, -1);
        return param;
    }

    function proxy(data) {
        return JSON.parse(JSON.stringify(data).replace(/i.pximg.net/g, "i.pixiv.cat"));
    }

    const api = "https://api.obfs.dev/api/pixiv/?";
    const timeout = 9000; //ms
    async function main(opt) {
        let res, req;
        setTimeout(() => {
            if (!res) return { detail: "network error" };
        }, timeout);
        req = await fetch(api + stringifySearch(opt));
        res = await req.json();
        return proxy(res);
    }

    async function resourceGetter(args) {
        let anwser = await main(args);
        if (Reflect.has(anwser,'detail')) {
            vant.Toast.fail("请求失败\n" + anwser.detail);
            return anwser.detail;
        }
        if (Reflect.has(anwser,'illusts')) return anwser.illusts;
        return anwser;
    }

    async function rank(mode = "day", quality = "medium") {
        let anwser = await resourceGetter({ type: "rank", mode });
        return anwser.map((v) => {
            return { id: v.id, src: v.image_urls[quality] };
        });
    }

    window.fetchData = { main, rank };
})();
