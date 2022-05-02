pragma solidity ^0.8.7;

import "https://github.com/OpenZeppelin/openzeppelin-contracts/blob/master/contracts/token/ERC1155/ERC1155.sol";

contract NFTMakeBSCContract is ERC1155 {
    uint256 public constant SWORD = 0;

    constructor() ERC1155("ipfs://QmX1xfbKoUEHtnoiVY9uEtH3X6YuJx2yzPg6ggp3zrFUzz/metadata/{id}.json") {
        _mint(msg.sender, SWORD, 100, "");
    }

    function contractURI() public view returns (string memory) {
        return "ipfs://QmX1xfbKoUEHtnoiVY9uEtH3X6YuJx2yzPg6ggp3zrFUzz/metadata/contract.json";
    }
}
