import React, { useState, useEffect } from "react";
import { fetchAllAddresses } from "../services/viaCepApi";
import AddressTable from "./AddressTable";
import Pagination from "./Pagination";
import useSorting from "../hooks/useSorting";
import "../styles/AddressView.css";

const AddressView = () => {
  const [addresses, setAddresses] = useState([]);
  const [error, setError] = useState(null);
  const [loading, setLoading] = useState(false);
  const [currentPage, setCurrentPage] = useState(1);
  const [totalPages, setTotalPages] = useState(1);
  const itemsPerPage = 10;

  const { orderBy, orderDirection, handleOrderByChange } =
    useSorting("localidade");

  useEffect(() => {
    const getAddresses = async () => {
      setLoading(true);
      try {
        const data = await fetchAllAddresses(
          currentPage,
          itemsPerPage,
          orderBy,
          orderDirection
        );
        setAddresses(data.data);
        setTotalPages(data.pagination.total_pages);
      } catch (error) {
        setError(error.message);
      } finally {
        setLoading(false);
      }
    };

    getAddresses();
  }, [currentPage, orderBy, orderDirection]);

  const handlePreviousPage = () => {
    if (currentPage > 1) setCurrentPage(currentPage - 1);
  };

  const handleNextPage = () => {
    if (currentPage < totalPages) setCurrentPage(currentPage + 1);
  };

  const headers = [
    "CEP",
    "Logradouro",
    "Complemento",
    "Bairro",
    "Cidade",
    "Estado",
    "IBGE",
    "DDD",
    "SIAFI",
  ];

  return (
    <div className="address-view">
      <h1>Endereços Armazenados</h1>
      {error && <p className="error-message">{error}</p>}
      {loading && <p className="loading-message">Carregando...</p>}
      <div className="sort-buttons">
        <button
          onClick={() => handleOrderByChange("localidade")}
          className="sort-button"
        >
          Ordenar por Localidade{" "}
          {orderBy === "localidade" && (orderDirection === "ASC" ? "↓" : "↑")}
        </button>
        <button
          onClick={() => handleOrderByChange("bairro")}
          className="sort-button"
        >
          Ordenar por Bairro{" "}
          {orderBy === "bairro" && (orderDirection === "ASC" ? "↓" : "↑")}
        </button>
        <button
          onClick={() => handleOrderByChange("uf")}
          className="sort-button"
        >
          Ordenar por UF{" "}
          {orderBy === "uf" && (orderDirection === "ASC" ? "↓" : "↑")}
        </button>
      </div>

      <AddressTable addresses={addresses} headers={headers} />

      <Pagination
        currentPage={currentPage}
        totalPages={totalPages}
        onPreviousPage={handlePreviousPage}
        onNextPage={handleNextPage}
      />
    </div>
  );
};

export default AddressView;
