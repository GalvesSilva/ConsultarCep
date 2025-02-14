import React, { useState } from 'react';
import SearchCEP from './components/SearchCEP';
import AddressTable from './components/AddressTable';
import AddressView from './components/AddressView';
import './App.css'; 

const headers = [
  'CEP', 'Logradouro', 'Complemento', 'Bairro', 'Cidade', 'Estado', 'IBGE', 'DDD', 'SIAFI'
];

const App = () => {
  const [addresses, setAddresses] = useState([]);
  const [refreshKey, setRefreshKey] = useState(0);

  const handleSearch = (address) => {
    setAddresses([address]);
    setRefreshKey((prevKey) => prevKey + 1);
  };

  return (
    <div className="App">
      <h1>Consulta de Endereços por CEP</h1>
      <SearchCEP onSearch={handleSearch} />
      <AddressTable addresses={addresses} headers={headers} />
      <AddressView key={refreshKey} />
    </div>
  );
};

export default App;