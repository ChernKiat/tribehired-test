pragma solidity ^0.8.7;

import "https://github.com/OpenZeppelin/openzeppelin-contracts/blob/master/contracts/token/ERC1155/ERC1155.sol";
import "https://github.com/OpenZeppelin/openzeppelin-contracts/blob/master/contracts/utils/math/SafeMath.sol";

contract NFTBulkMintContract is ERC1155 {
    using SafeMath for uint256;

    constructor() ERC1155("ipfs://QmX1xfbKoUEHtnoiVY9uEtH3X6YuJx2yzPg6ggp3zrFUzz/metadata/{id}.json") {
        for (uint i = 0; i < 1005; i++) {
            _mint(msg.sender, i, 1, "");
        }
    }

    function contractURI() public view returns (string memory) {
        return "ipfs://QmX1xfbKoUEHtnoiVY9uEtH3X6YuJx2yzPg6ggp3zrFUzz/metadata/contract.json";
    }
}
