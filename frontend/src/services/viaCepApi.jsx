import axios from "axios";

const BASE_URL = "http://localhost:8000/cep";


export const fetchAddressByCEP = async (cep) => {
  try {
    const response = await axios.get(`${BASE_URL}/${cep}`);
    if (response.data.error) {
      throw new Error(response.data.error);
    }
    return response.data;
  } catch (error) {
    throw new Error('Erro ao buscar o CEP');
  }
};

export const fetchAllAddresses = async (page, itemsPerPage, orderBy, orderDirection) => {
  try {
    const response = await axios.get(BASE_URL, {
      params: {
        page,
        limit: itemsPerPage,
        order_by: orderBy,
        order_direction: orderDirection,
      },
    });
    if (response.data.error) {
      throw new Error(response.data.error);
    }
    return response.data;
  } catch (error) {
    throw new Error('Erro ao buscar os endereços.');
  }
};