import { h } from 'preact';
import { useState, useEffect, useCallback } from 'preact/hooks';

export default function AfpOnboarding() {
  const [value, setValue] = useState(0);

  const increment = useCallback(() => {
    setValue(value + 1);
  }, [value]);

  return (
    <div>
      Counter: {value}
      <button onClick={increment}>Increment</button>
    </div>
  );
}
