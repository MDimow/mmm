import React from "react";

const TableLoading = ({ rows, cols, indeximg }) => {
  return (
    <div className="table-loading-container">
      <table>
        <thead>
          <tr>
            {[...Array(cols)].map((elem, i) => (
              <td key={i}>
                <span className="skeleton-box w-50p"></span>
              </td>
            ))}
          </tr>
        </thead>
        <tbody>
          {[...Array(rows)].map((elem, i) => (
            <tr key={i}>
              {[...Array(cols)].map((elem, i) => (
                <td key={i}>
                  <span
                    className={`skeleton-box ${
                      i == 0 && indeximg ? "w-40p h-40p border-circle" : "w-50"
                    }`}
                  ></span>
                </td>
              ))}
            </tr>
          ))}
        </tbody>
      </table>
    </div>
  );
};

export default TableLoading;
