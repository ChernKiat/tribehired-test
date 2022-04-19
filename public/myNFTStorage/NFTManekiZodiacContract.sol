pragma solidity ^0.8.7;

import "https://github.com/OpenZeppelin/openzeppelin-contracts/blob/master/contracts/access/Ownable.sol";
import "https://github.com/OpenZeppelin/openzeppelin-contracts/blob/master/contracts/token/ERC1155/ERC1155.sol";
import "https://github.com/OpenZeppelin/openzeppelin-contracts/blob/master/contracts/utils/math/SafeMath.sol";

contract NFTManekiZodiacContract is ERC1155, Ownable {
    using SafeMath for uint256;

    constructor() ERC1155("https://tacrcvv49znq.usemoralis.com/{id}.json") {
        for (uint i = 0; i < 1005; i++) {
            _mint(msg.sender, i, 1, "");
        }
    }

    // function mint(address to, uint256 id, uint256 amount) public onlyOwner {
    //     _mint(to, id, amount, "");
    // }

    // function burn(address from, uint256 id, uint256 amount) public {
    //     require(msg.sender == address);
    //     _burn(from, id, amount, "");
    // }

    function contractURI() public view returns (string memory) {
        return "https://tacrcvv49znq.usemoralis.com/contract.json";
    }
}
