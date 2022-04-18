pragma solidity ^0.8.7;

import "https://github.com/OpenZeppelin/openzeppelin-contracts/blob/master/contracts/token/ERC1155/ERC1155.sol";
import "https://github.com/OpenZeppelin/openzeppelin-contracts/blob/master/contracts/access/Ownable.sol";

contract NFTFullCourseContract is ERC1155, Ownable {

    uint256 public constant ADAM = 0;
    uint256 public constant EVE = 1;
    uint256 public constant SERPENT = 2;
    uint256 public constant ZODIAC = 3;
    uint256 public constant COUPLE = 4;
    uint256 public constant GENESIS = 5;

    constructor() ERC1155("https://tacrcvv49znq.usemoralis.com/{id}.json") {
        _mint(msg.sender, ADAM, 1, "");
        _mint(msg.sender, EVE, 1, "");
        _mint(msg.sender, SERPENT, 1, "");
        _mint(msg.sender, ZODIAC, 12, "");
        _mint(msg.sender, COUPLE, 66, "");
        _mint(msg.sender, GENESIS, 924, "");
    }

    // function mint(address to, uint256 id, uint256 amount) public onlyOwner {
    //  _mint(to, id, amount, "");
    // }

    // function burn(address from, uint256 id, uint256 amount) public {
    //     require(msg.sender == address);
    //  _burn(from, id, amount, "");
    // }

    function contractURI() public view returns (string memory) {
        return "https://tacrcvv49znq.usemoralis.com/contract.json";
    }
}
