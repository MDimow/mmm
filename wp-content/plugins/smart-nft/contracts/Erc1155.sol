// SPDX-License-Identifier: MIT
pragma solidity ^0.8.13;

import "@openzeppelin/contracts/token/ERC1155/ERC1155.sol";
import "@openzeppelin/contracts/access/Ownable.sol";
import "@openzeppelin/contracts/security/Pausable.sol";
import "@openzeppelin/contracts/token/ERC1155/extensions/ERC1155Burnable.sol";
import "@openzeppelin/contracts/token/ERC1155/extensions/ERC1155Supply.sol";

contract Smartnft_Pro_ERC1155 is ERC1155, Ownable, Pausable, ERC1155Burnable, ERC1155Supply {
    string public name;

    string public symbol;

    uint256 public tokenCounts;

    mapping( uint256 => string ) private _tokenURIs;

    constructor( string memory _name, string memory _symbol )  ERC1155("") {
       name = _name;
       symbol = _symbol;
    }

    function uri( uint256 tokenId ) public view override returns( string memory ){
        return _tokenURIs[tokenId];
    }


    function pause() public onlyOwner {
        _pause();
    }

    function unpause() public onlyOwner {
        _unpause();
    }

    function mint( uint256 amount, string memory _uri )
        public payable
    {
        //uint256 newId = ids;
        tokenCounts += 1;
        _tokenURIs[tokenCounts] = _uri;
        _mint(msg.sender, tokenCounts, amount, "0x0");
    }

    function gift_mint( uint256 amount, string memory _uri, address owner )
     	public payable
    {
        //uint256 newId = ids;
        tokenCounts += 1;
        _tokenURIs[tokenCounts] = _uri;
        _mint(owner, tokenCounts, amount, "0x0");
    }

    function mintBatch( uint256[] memory amounts, string[] memory _uris )
        public payable
    {
        uint256[] memory ids = new uint256[](amounts.length);
        for( uint256 i= 0; i < amounts.length; i++ ) {
            tokenCounts += 1;
           ids[i] = tokenCounts; 
           _tokenURIs[tokenCounts] = _uris[i];
        }

        _mintBatch( msg.sender, ids, amounts, "0x0" );
    }
    
    function withdrawBalance() public onlyOwner returns( bool ) {
      uint _balance = address(this).balance;
      
      payable( msg.sender ).transfer( _balance );

      return true;
    }

    function getBalance () public view returns ( uint ) {
        return address(this).balance;
    }

    function _beforeTokenTransfer(address operator, address from, address to, uint256[] memory ids, uint256[] memory amounts, bytes memory data)
        internal
        whenNotPaused
        override(ERC1155, ERC1155Supply)
    {
        super._beforeTokenTransfer(operator, from, to, ids, amounts, data);
    }

    function supportsInterface( bytes4 interfaceId ) public pure override returns(bool){
        return interfaceId == 0xd9b67a26 || interfaceId == 0x0e89341c;
    }

}
