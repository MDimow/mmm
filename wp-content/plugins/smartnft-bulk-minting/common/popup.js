import { createPortal } from "react-dom";

export const Popup = ({ children }) => {
  return createPortal(
    <div className="smart-nft-popup__container open">
      <div className="smart-nft-popup__inner">{children}</div>
    </div>,
    document.querySelector("body")
  );
};
