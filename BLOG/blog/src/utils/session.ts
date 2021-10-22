export class session {
    static set(key: string, value: any) {
        sessionStorage.setItem(key, JSON.stringify(value));
    }
    static get(key: string) {
        var d = sessionStorage.getItem(key);
        if (!d) return d;
        return JSON.parse(d);
    }
    static remove(key: string) {
        sessionStorage.removeItem(key);
    }
    static clear() {
        sessionStorage.clear();
    }
    static count() {
        return sessionStorage.length;
    }
}
