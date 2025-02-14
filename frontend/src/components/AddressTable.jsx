import React from "react";
import "../styles/AddressTable.css";

const AddressTable = ({ addresses, headers }) => {
  const headerToPropertyMap = {
    CEP: "cep",
    Logradouro: "logradouro",
    Complemento: "complemento",
    Bairro: "bairro",
    Cidade: "localidade",
    Estado: "uf",
    IBGE: "ibge",
    DDD: "ddd",
    SIAFI: "siafi",
  };

  return (
    <div className="address-table-container">
      <table className="address-table">
        <thead>
          <tr>
            {headers.map((header, index) => (
              <th key={index}>{header}</th>
            ))}
          </tr>
        </thead>
        <tbody>
          {addresses.length === 0 ? (
            <tr>
              <td
                colSpan={headers.length}
                className="address-table-empty-message"
              >
                Nenhum endereço encontrado.
              </td>
            </tr>
          ) : (
            addresses.map((address) => (
              <tr key={address.cep} className="address-table-row">
                {headers.map((header, index) => {
                  const property = headerToPropertyMap[header];
                  return <td key={index}>{address[property]}</td>;
                })}
              </tr>
            ))
          )}
        </tbody>
      </table>
    </div>
  );
};

export default AddressTable;
