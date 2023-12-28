import React from "react";

const NFT_PER_PAGE = 10;
const FRONTENDMEDIAURL = importer_local.SNFT_IMPORTER_MEDIA_URL;
const SETTINGS = importer_local.SETTINGS;

const Pagination = ({ totalNfts, setCurPage, curPage, setLoading }) => {
  //checking if pagination is enable from dashboard
  if (SETTINGS.pagination === "false") return;
  //main component code
  if (totalNfts <= NFT_PER_PAGE) return null;
  console.log(totalNfts);

  const pages = Math.ceil(totalNfts / NFT_PER_PAGE);
  console.log("pages: ", pages);

  const next = () => {
    if (curPage >= pages) return;
    setCurPage(curPage + 1);
    setLoading(true);
  };

  const prev = () => {
    if (curPage <= 1) return;
    setCurPage(curPage - 1);
    setLoading(true);
  };

  //when pages is more then 3 page
  if (pages > 3) {
    //when cur page cant be first item in page display list
    if (curPage + 2 >= pages) {
      const curPagePosition = pages - curPage - 1;

      //when cur page is middle item in page display list
      if (curPagePosition == 1) {
        const leftPage = [
          <Page
            pageNo={curPage - 1}
            key={curPage - 1}
            setCurPage={setCurPage}
            setLoading={setLoading}
            curPage={curPage}
          />,
          <Page
            pageNo={curPage}
            key={curPage}
            setCurPage={setCurPage}
            curPage={curPage}
            setLoading={setLoading}
          />,
          <Page
            pageNo={curPage + 1}
            key={curPage + 1}
            setCurPage={setCurPage}
            setLoading={setLoading}
            curPage={curPage}
          />,
        ];
        return (
          <div className="pagination">
            <img
              onClick={prev}
              src={`${FRONTENDMEDIAURL}left-arrow.svg`}
              alt="left arrow"
            />
            {leftPage}
            ....
            <Page
              pageNo={pages}
              key={pages}
              setLoading={setLoading}
              setCurPage={setCurPage}
              curPage={curPage}
            />
            <img
              onClick={next}
              src={`${FRONTENDMEDIAURL}right-arrow.svg`}
              alt="right arrow"
            />
          </div>
        );
      }

      //when cur page is last item in page display list
      if (curPagePosition == 0) {
        const leftPage = [
          <Page
            pageNo={curPage - 2}
            key={curPage - 2}
            setCurPage={setCurPage}
            setLoading={setLoading}
            curPage={curPage}
          />,
          <Page
            pageNo={curPage - 1}
            key={curPage - 1}
            setCurPage={setCurPage}
            setLoading={setLoading}
            curPage={curPage}
          />,
          <Page
            setLoading={setLoading}
            pageNo={curPage}
            key={curPage}
            setCurPage={setCurPage}
            curPage={curPage}
          />,
        ];
        return (
          <div className="pagination">
            <img
              onClick={prev}
              src={`${FRONTENDMEDIAURL}left-arrow.svg`}
              alt="left arrow"
            />
            {leftPage}
            ....
            <Page
              setLoading={setLoading}
              pageNo={pages}
              key={pages}
              setCurPage={setCurPage}
              curPage={curPage}
            />
            <img
              onClick={next}
              src={`${FRONTENDMEDIAURL}right-arrow.svg`}
              alt="right arrow"
            />
          </div>
        );
      }
      //when cur page is first item in page display list
    } else {
      const leftPage = [
        <Page
          setLoading={setLoading}
          pageNo={curPage}
          key={curPage}
          setCurPage={setCurPage}
          curPage={curPage}
        />,
        <Page
          setLoading={setLoading}
          pageNo={curPage + 1}
          key={curPage + 1}
          setCurPage={setCurPage}
          curPage={curPage}
        />,
        <Page
          setLoading={setLoading}
          pageNo={curPage + 2}
          key={curPage + 2}
          setCurPage={setCurPage}
          curPage={curPage}
        />,
      ];
      return (
        <div className="pagination">
          <img
            onClick={prev}
            src={`${FRONTENDMEDIAURL}left-arrow.svg`}
            alt="left arrow"
          />
          {leftPage}
          ....
          <Page
            setLoading={setLoading}
            pageNo={pages}
            key={pages}
            setCurPage={setCurPage}
            curPage={curPage}
          />
          <img
            onClick={next}
            src={`${FRONTENDMEDIAURL}right-arrow.svg`}
            alt="right arrow"
          />
        </div>
      );
    }
    //
  } else {
    //when pages is less then 3 item
    return (
      <div className="pagination">
        <img
          onClick={prev}
          src={`${FRONTENDMEDIAURL}left-arrow.svg`}
          alt="left arrow"
        />
        {[...Array(pages)].map((cur, i) => (
          <Page
            pageNo={i + 1}
            setCurPage={setCurPage}
            setLoading={setLoading}
            key={i + 1}
            curPage={curPage}
          />
        ))}
        <img
          onClick={next}
          src={`${FRONTENDMEDIAURL}right-arrow.svg`}
          alt="right arrow"
        />
      </div>
    );
  }

  //when pages is more then 3 and last page
  if (curPage === pages) {
    const rightPages = [
      <Page
        setLoading={setLoading}
        pageNo={pages - 2}
        key={pages - 2}
        setCurPage={setCurPage}
        curPage={curPage}
      />,
      <Page
        setLoading={setLoading}
        pageNo={pages - 1}
        key={pages - 1}
        setCurPage={setCurPage}
        curPage={curPage}
      />,
      <Page
        setLoading={setLoading}
        pageNo={pages}
        key={pages}
        setCurPage={setCurPage}
        curPage={curPage}
      />,
    ];
    return (
      <div className="pagination">
        <img
          onClick={prev}
          src={`${FRONTENDMEDIAURL}left-arrow.svg`}
          alt="left arrow"
        />
        <Page
          curPage={curPage}
          setLoading={setLoading}
          pageNo={1}
          key={1}
          setCurPage={setCurPage}
        />
        ....
        {rightPages}
        <img
          onClick={next}
          src={`${FRONTENDMEDIAURL}right-arrow.svg`}
          alt="right arrow"
        />
      </div>
    );
  }

  return null;
};

const Page = ({ pageNo, setCurPage, setLoading, curPage }) => {
  return (
    <span
      className={`pageno ${curPage === pageNo ? "current" : ""}`}
      onClick={() => {
        if (curPage === pageNo) return;
        setLoading(true);
        setCurPage(pageNo);
      }}
    >
      {pageNo}
    </span>
  );
};

export { Pagination };
