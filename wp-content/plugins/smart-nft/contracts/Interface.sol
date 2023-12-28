// SPDX-License-Identifier: MIT
pragma solidity ^0.8.13;

interface ERC1155_Interface {
  function balanceOf( address account, uint256 id ) external view returns( uint256 );
  function safeTransferFrom(address from, address to, uint256 id, uint256 amount, bytes memory data) external;
  function gift_mint( uint256 amount, string memory uri, address owner ) external;
}

interface ERC721_Interface {
  function ownerOf(uint256 tokenId) view external returns (address);
  function safeTransferFrom(address from,address to,uint256 tokenId) external;
  function gift_mint(string memory tokenUri, address owner) external returns(uint256);
}

interface ERC20_Interface {
  function transferFrom( address from, address to, uint256 amount ) external returns ( bool );	
}

interface Common_Interface {
  function isApprovedForAll(address account, address operator) view external returns( bool );
}

