import React, { useContext, useState } from "react";
import { CreateNftContext } from "./state";
import {
  SLUG,
  BACKEND_AJAX_URL,
  BACKENDMEDIAURL,
} from "../../../../../common/store";
import useIPFSProvider from "../../../../../common/hook/ipfs.hook";
import useNft from "../../../../../common/hook/useNft.hook";
import { Popup } from "../common/popup";
import { errorMessage } from "../../../../../common/component/message/error";
import { successMessage } from "../../../../../common/component/message/success";
const { __ } = wp.i18n;

const AddQueueBtn = ({ web3Provider }) => {
  const { state, dispatch } = useContext(CreateNftContext);
  const { uploadIpfsUsingInfura } = useIPFSProvider();
  const { uploadToWPMedia, uploadBase64FileToMediaLibrary } = useNft();
  const [open, setOpen] = useState(false);

  const addToQueue = async () => {
    //ERROR
    if (!web3Provider?.account[0]) {
      return errorMessage("No WEB3 account found.");
    }
    if (!state.meta.name || !state.meta.description || !state.mediaBinary) {
      return errorMessage("Give proper information.");
      //throw new Error("Give proper information.");
    }

    try {
      setOpen(true);
      //UPLOAD DATA
      const mediaUrl = await uploadIpfsUsingInfura(state.mediaBinary);
      let mediaThumb = await uploadToWPMedia(state.file);

      //IF WP IMAGE UPLOAD FAIL THEN UPLOAD THE FILE DIRECLY
      if (!mediaThumb?.attach_url) {
        //if its img file then
        if (state?.fileType?.startsWith("image")) {
          mediaThumb.attach_url = await uploadBase64FileToMediaLibrary({
            base64File: state?.mediaUrl,
            title: state?.meta?.name,
            mimeType: state?.fileType,
          });
        }
        //if its audio file then
        if (state?.fileType?.startsWith("audio")) {
          mediaThumb.attach_url = await uploadBase64FileToMediaLibrary({
            base64File: state?.thumbnailMediaUrl,
            title: state?.meta?.name,
            mimeType: state?.file?.type, //audio type file fileType not in state.fileType
          });
        }

        //if its video file then
        if (state?.fileType?.startsWith("video")) {
          mediaThumb.attach_url = await uploadBase64FileToMediaLibrary({
            base64File: state?.mediaUrl,
            title: state?.meta?.name,
            mimeType: state?.fileType,
          });
        }
      }

      const newState = { ...state };
      const meta = {
        ...newState.meta,
        image: mediaUrl,
        attributes: [...state.properties, ...state.labels, ...state.stats],
      };

      newState.meta = meta;
      newState.mediaUrl = mediaUrl;
      newState.creator = web3Provider.account[0].toLowerCase();
      newState.owners = [web3Provider.account[0].toLowerCase()];
      newState.thumbnailMediaUrl = { ...mediaThumb };

      newState.jsonUrl = await uploadIpfsUsingInfura(
        JSON.stringify(newState.meta)
      );

      //delete unnecessary property
      delete newState.mediaBinary;
      delete newState.file;

      const res = await jQuery.ajax({
        type: "post",
        url: BACKEND_AJAX_URL,
        data: {
          action: "nbm_add_to_queue",
          account: web3Provider.account[0],
          chainId: web3Provider.network.chainId,
          standard: newState.standard,
          meta: newState,
        },
      });
      //console.log(res);
      window.location.reload();
    } catch (err) {
      console.error(err);
      setOpen(false);
      errorMessage("Something is wrong! Item queuing fail");
    }
  };

  return (
    <>
      <button onClick={addToQueue}>{__("Add to queue", SLUG)}</button>
      {open ? (
        <Popup>
          <img
            className="rotating"
            src={`${BACKENDMEDIAURL}/loaders/loading.svg`}
          />
          <h3>{__("Wait! We are queueing your nft.")}</h3>
        </Popup>
      ) : null}
    </>
  );
};

export default AddQueueBtn;
