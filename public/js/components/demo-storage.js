// Class for chucking data into the browsers Local Storage
export class DemoStorage {

    // Use setItem to store your data with a key name of whatever you want
    static setItem(key, data) {
        window.localStorage.setItem(key, JSON.stringify(data));
    }

    // Retrieve the data you stored using your key name
    static getItem(key) {
        return window.localStorage.getItem(key);
    }

}
