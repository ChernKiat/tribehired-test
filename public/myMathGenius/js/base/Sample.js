class Game {
    constructor(brand) {
        this.carname = brand;
    }
    present(x) {
        return x + ", I have a " + this.carname;
    }
    static hello(x) {
        return "Hello " + x.carname;
    }
    get cnam() {
        return this.carname;
    }
    set cnam(x) {
        this.carname = x;
    }
}
