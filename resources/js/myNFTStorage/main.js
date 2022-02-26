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
        // .then(res => console.log(res, 'lol')));
        .then(res => {nft.metadata = res})
        .then(res => {
            const options = { address: CONTRACT_ADDRESS, token_id: id, chain: "rinkeby"};
            return Moralis.Web3API.token.getTokenIdOwners(options);
        })
        // .then(res => console.log(res, 'yay')));
        .then((res) => {
            nft.owners = [];
            res.result.forEach(element => {
                nft.owners.push(element.owner_of);
            });
            return nft;
        }))
        // .then(() => {return nft;}));
    }
    return Promise.all(promises);
}

// renderInventory
// nft.metadata.image // nft.metadata.name // your metadata
// nft.amount // total copy
// nft.owners.length // total owners

async function initializApp() {
    let currentUser = Moralis.User.current();
    if (!currentUser) {
        currentUser = await Moralis.Web3.authenticate();
    }
    alert("Your signed in and ready to go Bro!");

    const options = { address: CONTRACT_ADDRESS, chain: "rinkeby"};
    let NFTs = await Moralis.Web3API.token.getAllTokenIds(options);
    let NFTsWithMetadata = fetchNFTMetadata(NFTs.result);
    console.log(NFTs, NFTsWithMetadata, 'o0o');

    // transfer();
}

initializApp();


let web3; // use Remix's methods

async function initializAppWithAccount() {
    let currentUser = Moralis.User.current();
    if (!currentUser) {
        currentUser = await Moralis.Web3.authenticate();
    }

    web3 = await Moralis.Web3.enable();
    let accounts = await web3.eth.getAccounts();
    console.log(accounts[0], 'wtf'); // current signed in account

    mint();
}

async function mint() {
    const contract = new web3.eth.Contract(remix_abi, CONTRACT_ADDRESS); // use Remix's methods
    // contract.methods.mint(address to, uint256 id, uint256 amount) // follow your solidity contract
    //     .send({from: accounts[0], value: 0}).on('receipt', function(receipt) {
    //         alert('mint done!');
    //     });
}

// you might need to comment out the if statement that surrounded currentUser to call the authenticate method
async function transfer() { // method caller removed temporary
    const options = { type: 'erc1155', receiver: '0x2775490B26e92307909408651e87bfCD83c185A4', contract_address: CONTRACT_ADDRESS, token_id: 5, amount: 1};
    let result = await Moralis.transfer(options);
    console.log(result);
}
