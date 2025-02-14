import { useState } from 'react';

const useSorting = (initialField, initialDirection = 'ASC') => {
  const [orderBy, setOrderBy] = useState(initialField);
  const [orderDirection, setOrderDirection] = useState(initialDirection);

  const handleOrderByChange = (field) => {
    if (orderBy === field) {
      setOrderDirection(orderDirection === 'ASC' ? 'DESC' : 'ASC');
    } else {
      setOrderBy(field);
      setOrderDirection('ASC');
    }
  };

  return { orderBy, orderDirection, handleOrderByChange };
};

export default useSorting;