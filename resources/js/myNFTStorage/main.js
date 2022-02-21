// let mix = require('laravel-mix');
// require('dotenv').config();

const MORALIS_APPLICATION_ID__ULTIMATE_NFT = 'a0ooNwmpY3RCxLhagR3xjencorgS6RXEUcaw841e';
const MORALIS_SERVER_URL__ULTIMATE_NFT = 'https://tacrcvv49znq.usemoralis.com:2053/server/';
const CONTRACT_ADDRESS = '0xf255366f51da383a2869f6280254f0ef1d0d0350'; // copy the contract address from url

// Moralis.initialize(MORALIS_APPLICATION_ID__ULTIMATE_NFT)
// Moralis.serverURL=MORALIS_SERVER_URL__ULTIMATE_NFT;
Moralis.start({ serverUrl:MORALIS_SERVER_URL__ULTIMATE_NFT, appId:MORALIS_APPLICATION_ID__ULTIMATE_NFT });

function fetchNFTMetadata(NFTs) {
    let promises = [];
    for (let i = 0; i <NFTs.length; i++) {
        let nft = NFTs[i];
        let id = nft.token_id;
        promises.push(fetch(MORALIS_SERVER_URL__ULTIMATE_NFT + "functions/getNFT?_ApplicationId=" + MORALIS_APPLICATION_ID__ULTIMATE_NFT + "&nft_id=" + id)
        .then(res => res.json())
        .then(res => JSON.parse(res.result))
        // .then(res => console.log(res, 'lol')))
        .then(res => {nft.metadata = res})
        .then(res => {
            const options = { address: CONTRACT_ADDRESS, token_id: id, chain: "rinkeby"};
            return Moralis.Web3API.token.getTokenIdOwners(options);
        })
        .then(() => {return nft;}))
    }
    return Promise.all(promises)
}

// renderInventory

async function initializApp() {
    let currentUser = Moralis.User.current();
    if (!currentUser) {
        currentUser = await Moralis.Web3.authenticate();
    }
    alert("Your signed in and ready to go Bro!")

    const options = { address: CONTRACT_ADDRESS, chain: "rinkeby"};
    let NFTs = await Moralis.Web3API.token.getAllTokenIds(options);
    let NFTsWithMetadata = fetchNFTMetadata(NFTs.result);
    console.log(NFTs, NFTsWithMetadata, 'o0o')
}

initializApp()
