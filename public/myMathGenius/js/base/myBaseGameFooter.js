    function randomInt(N)
    {
        // % 1 is needed because some implementations of Math.random() can
        // actually return 1 (early version of Opera for example).
        // | 0 does the same as Math.floor() would here, but is probably
        // slightly quicker.
        // For details, see: http://www.merlyn.demon.co.uk/js-randm.htm
        return (N * (Math.random() % 1)) | 0;
    }

    function getCookie(cookieName) {
        var theCookie = document.cookie;
        if (!theCookie) return 0;
        var cookies = theCookie.split("; ");
        for (var i = 0; i < cookies.length; ++i) {
            var nameVal = cookies[i].split("=");
            if (nameVal[0] == cookieName) return nameVal[1];
        }
        return 0;
    }

    /*

    function getBestPlay() {
        return getCookie("sscrable_bestplay");
    }

    function setBestPlay(value) {
        document.cookie = "sscrable_bestplay=" + value + ";expires=Tue, 19-Jan-2038 03:14:07 GMT";
    }

    */
})();
