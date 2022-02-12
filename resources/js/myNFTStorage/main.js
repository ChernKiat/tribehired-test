let mix = require('laravel-mix');
require('dotenv').config();

Moralis.initialize(process.env.MORALIS_APPLICATION_ID__ULTIMATE_NFT)
Moralis.serverURL=process.env.MORALIS_SERVER_URL__ULTIMATE_NFT;

function fetchNFTMetadata(NFTs) {
    for (let i = 0; i <NFTs.length; i++) {
        let nft = NFTs[i];
        let id = nft.token_id;
        // call moralis cloud function -> static jason file
        fetch (process.env.MORALIS_SERVER_URL__ULTIMATE_NFT + "?_ApplicationId=" + process.env.MORALIS_APPLICATION_ID__ULTIMATE_NFT + "&nfd_id=" + id)
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

    const options = { address: "0xc1f5b3fe13906882c7c07142ca09727ec44a2c8a", chain: "rinkeby"};
    let NFTs = await Moralis.Web3API.token.getAllTokenIds(options);
    fetchNFTMetadata(NFTs.result);
}
