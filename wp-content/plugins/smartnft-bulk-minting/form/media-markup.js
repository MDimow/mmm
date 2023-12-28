import React, { useContext } from "react";
import { CreateNftContext } from "./state";
import { FRONTENDMEDIAURL } from "../../../../../common/store";

const ImageMedia = () => {
  const { state, dispatch } = useContext(CreateNftContext);

  if (!state.fileType?.startsWith("image")) return null;

  return <img className="inbox-media" src={state.mediaUrl} />;
};

const ThumbnailMedia = () => {
  const { state, dispatch } = useContext(CreateNftContext);

  if (!state.thumbnailMediaUrl) return null;

  return <img className="inbox-media" src={state.thumbnailMediaUrl} />;
};

const AudioMedia = () => {
  const { state, dispatch } = useContext(CreateNftContext);

  if (!state.fileType?.startsWith("audio")) return null;

  return (
    <audio
      controlsList="nodownload noplaybackrate"
      controls
      className="inbox-media"
      src={state.mediaUrl}
    />
  );
};

const VideoMedia = () => {
  const { state, dispatch } = useContext(CreateNftContext);

  if (!state.fileType?.startsWith("video")) return null;

  return (
    <video
      controlsList="nodownload"
      autoPlay
      controls
      className="inbox-media"
      src={state.mediaUrl}
    />
  );
};

const CloseMedia = () => {
  const { state, dispatch } = useContext(CreateNftContext);

  if (!state.mediaUrl) return null;

  const removeMedia = () => {
    dispatch({ type: "SET_MEDIA_URL", payload: null });
    dispatch({ type: "SET_FILE_TYPE", payload: null });
    dispatch({ type: "SET_MEDIA_BINARY", payload: null });
  };

  return (
    <span className="close-media" onClick={removeMedia}>
      <img src={`${FRONTENDMEDIAURL}cross.svg`} />
    </span>
  );
};

const CloseThumbnail = () => {
  const { state, dispatch } = useContext(CreateNftContext);

  if (!state.thumbnailMediaUrl) return null;

  const removeMedia = () => {
    dispatch({ type: "SET_THUMBNAIL_BINARY", payload: null });
    dispatch({ type: "SET_THUMBNAIL_MEDIA_URL", payload: null });
  };

  return (
    <span className="close-media" onClick={removeMedia}>
      <img src={`${FRONTENDMEDIAURL}cross.svg`} />
    </span>
  );
};

export {
  ImageMedia,
  AudioMedia,
  VideoMedia,
  CloseMedia,
  ThumbnailMedia,
  CloseThumbnail,
};
