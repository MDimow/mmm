import React, { useContext } from "react";
import { CreateNftContext } from "./state";
import { SLUG } from "../../../../../common/store";
import { ImageMedia, VideoMedia, AudioMedia, CloseMedia } from "./media-markup";
const { __ } = wp.i18n;

const maxuploadsize = 12 * 1024 * 1024;

const MediaUpload = () => {
  const { state, dispatch } = useContext(CreateNftContext);
  console.log(state);

  const processFile = (e) => {
    const file = e.target.files[0];
    console.log(file);

    if (!file) return null;

    if (file.size > maxuploadsize) {
      throw new Error("large file size");
    }

    if (file.type.startsWith("image")) {
      dispatch({ type: "SET_FILE", payload: file });
    }

    const binaryReader = new FileReader();
    const dataUrlReader = new FileReader();
    binaryReader.readAsArrayBuffer(file);
    dataUrlReader.readAsDataURL(file);

    binaryReader.addEventListener("load", () => {
      dispatch({ type: "SET_MEDIA_BINARY", payload: binaryReader.result });
    });

    dataUrlReader.addEventListener("load", () => {
      dispatch({ type: "SET_MEDIA_URL", payload: dataUrlReader.result });
      dispatch({ type: "SET_FILE_TYPE", payload: file.type });
    });
  };

  return (
    <div className="form-img-upload__upload-box">
      <ImageMedia />
      <VideoMedia />
      <AudioMedia />
      <CloseMedia />

      {!state.mediaUrl && (
        <div>
          <p className="pra-one">
            {__(
              `JPG, PNG, AVIF, GIF, WEBP, GLB, MP4 or MP3. Max ${
                maxuploadsize / 1024 / 1024
              } mb.`,
              SLUG
            )}
          </p>
          <label htmlFor="upload-btn">
            {__("Choose file", SLUG)}
            <input
              className="form-img-upload__file-input"
              type="file"
              id="upload-btn"
              onChange={processFile}
            />
          </label>
        </div>
      )}
    </div>
  );
};

export default MediaUpload;
