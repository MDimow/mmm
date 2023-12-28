// SPDX-License-Identifier: MIT
pragma solidity ^0.8.13;

import "@openzeppelin/contracts/token/ERC721/ERC721.sol";
import "@openzeppelin/contracts/token/ERC721/extensions/ERC721Enumerable.sol";
import "@openzeppelin/contracts/token/ERC721/extensions/ERC721URIStorage.sol";

contract Smartnft_Pro_ERC721 is  ERC721, ERC721Enumerable, ERC721URIStorage {
  address private _contract_owner;
  uint256 public tokenIds;

  constructor( string memory _name, string memory _symbol ) ERC721( _name, _symbol ) {
      _contract_owner = msg.sender;
  }

  function _beforeTokenTransfer(
    address from, 
    address to, 
    uint256 tokenId, 
    uint256 batchSize
  )internal override(ERC721, ERC721Enumerable)
  {
      super._beforeTokenTransfer(from, to, tokenId, batchSize);
  }

  function _burn(uint256 tokenId) internal override(ERC721, ERC721URIStorage) {
      super._burn(tokenId);
  }

  function tokenURI(uint256 tokenId)
    public
    view
    override(ERC721, ERC721URIStorage)
    returns (string memory)
  {
    return super.tokenURI(tokenId);
  }

  function supportsInterface(bytes4 interfaceId)
    public
    view
    override(ERC721, ERC721Enumerable)
    returns (bool)
  {
    return super.supportsInterface(interfaceId);
  }

  function mint( string memory tokenUri ) public payable returns ( uint256 ) {
     tokenIds++; 
     uint256 newTokenId = tokenIds;
     //`_safeMint, _setTokenURI` fn is comming from ERC721 contract that we extend. 
	   _safeMint(msg.sender, newTokenId);
	   _setTokenURI( newTokenId,tokenUri );

     return newTokenId;
  }

  function gift_mint( string memory tokenUri, address owner ) public payable returns ( uint256 ) {
     tokenIds++; 
     uint256 newTokenId = tokenIds;
     //`_safeMint, _setTokenURI` fn is comming from ERC721 contract that we extend. 
	   _safeMint(owner, newTokenId);
	   _setTokenURI( newTokenId,tokenUri );

     return newTokenId;
  }

  function burn( uint256 tokenId ) public {
    require(
      msg.sender == ownerOf( tokenId ) &&
      _exists( tokenId ) ,
      "Not owner or not exists."
    );

    _burn( tokenId );
  }

	function withdrawBalance() public returns( bool ) {
		require(
			msg.sender == _contract_owner,
			"Not admin"
		);

		uint _balance = address(this).balance;
		
		payable( _contract_owner ).transfer( _balance );

    return true;
	}

	function getBalance () public view returns ( uint ) {
			return address(this).balance;
	}

}

