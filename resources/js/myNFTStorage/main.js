// let mix = require('laravel-mix');
// require('dotenv').config();

let MORALIS_APPLICATION_ID__ULTIMATE_NFT = 'a0ooNwmpY3RCxLhagR3xjencorgS6RXEUcaw841e';
let MORALIS_SERVER_URL__ULTIMATE_NFT = 'https://tacrcvv49znq.usemoralis.com:2053/server/';

// Moralis.initialize(MORALIS_APPLICATION_ID__ULTIMATE_NFT)
// Moralis.serverURL=MORALIS_SERVER_URL__ULTIMATE_NFT;

Moralis.start({ serverUrl:MORALIS_SERVER_URL__ULTIMATE_NFT, appId:MORALIS_APPLICATION_ID__ULTIMATE_NFT });

function fetchNFTMetadata(NFTs) {
    for (let i = 0; i <NFTs.length; i++) {
        let nft = NFTs[i];
        let id = nft.token_id;
        // call moralis cloud function -> static jason file
        fetch (MORALIS_SERVER_URL__ULTIMATE_NFT + "?_ApplicationId=" + MORALIS_APPLICATION_ID__ULTIMATE_NFT + "&nfd_id=" + id)
        .then(res => res.json())
        .then(res => JSON.parse(res.result))
        .then(res => console.log(res))
    }
}

async function initializApp() {
    let currentUser = Moralis.User.current();
    if (!currentUser) {
        currentUser = await Moralis.Web3.authenticate();
    }
    alert("Your signed in and ready to go Bro!")

    const options = { address: "0xaa43e38158d656e2B366f4D25274606962c09D72", chain: "rinkeby"};
    let NFTs = await Moralis.Web3API.token.getAllTokenIds(options);
    fetchNFTMetadata(NFTs.result);
}

initializApp()
