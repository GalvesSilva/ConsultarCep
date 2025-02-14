import React, { useState } from "react";
import { fetchAddressByCEP } from "../services/viaCepApi";
import '../styles/SearchCEP.css'; 

const SearchCEP = ({ onSearch }) => {
  const [cep, setCep] = useState("");

  const handleSearch = async () => {
    if (cep.length === 8) {
      try {
        const address = await fetchAddressByCEP(cep);
        onSearch(address);
      } catch (error) {
        alert(error.message);
      }
    } else {
      alert("CEP deve ter 8 dígitos");
    }
  };

  return (
    <div className="searchForm">
      <input
        className="search-bar"
        type="text"
        value={cep}
        onChange={(e) => setCep(e.target.value.replace(/\D/g, ""))}
        placeholder="Digite o CEP"
        maxLength="8"
      />
      <button className="submit-button" onClick={handleSearch}>Buscar</button>
    </div>
  );
};

export default SearchCEP;
