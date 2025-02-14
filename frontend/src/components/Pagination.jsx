import React from 'react';
import '../styles/Pagination.css';

const Pagination = ({ currentPage, totalPages, onPreviousPage, onNextPage }) => {
  return (
    <div className='pagination'>
      <button onClick={onPreviousPage} disabled={currentPage === 1}>
        Anterior
      </button>
      <span>
        Página {currentPage} de {totalPages}
      </span>
      <button onClick={onNextPage} disabled={currentPage === totalPages}>
        Próxima
      </button>
    </div>
  );
};

export default Pagination;