import { h } from 'preact';
import { useEffect, useState } from 'preact/hooks';
import axios from 'axios';

export default function AfpPayment() {
  const [sellers, setSellers] = useState([]);
  const [selectedAccount, setSelectedAccount] = useState('');
  const [selectedAccountName, setSelectAccountName] = useState('');

  const value = 1499;
  const marketplaceFee = Math.round((value / 100) * 10);
  const merchantTotal = value - marketplaceFee;

  useEffect(() => {
    async function getData() {
      const { data } = await axios({
        method: 'POST',
        url: '/api/db/getSellers',
        data: {
          demo: 'kingfisher',
        },
      });

      setSellers(data.sellers);
    }
    getData();
  }, []);

  function setDriver(target) {
    updateState(s => {
      s.accountCodes = [target.value];
    });
    const value = target.value;
    const text = target.selectedOptions[0].text;
    setSelectedAccount(value);
    setSelectAccountName(text);
  }

  if (sellers.length < 0) return null;

  return (
    <div className="kingfisher">
      <div className="bg-gray-800 bg-opacity-80 rounded p-8 mx-auto w-full mt-16">
        <div className="mx-auto flex space-x-8">
          <div className="flex-1 ">
            <span className="text-white font-medium text-2xl">Payment Method</span>
            <div className="mt-4 ">

            </div>
          </div>
          <div className="flex-1">
            <span className="text-white font-medium text-2xl">Summary</span>
            <div className="bg-white rounded mt-4 p-4">
              <div className="flex p-4 border-b-2">
                <span className="w-3/4 font-bold">1x Trades Callout</span>
                <span className="w-1/4 text-right font-black">£{value / 100}</span>
              </div>
              <div className="flex pt-4 px-4">
                <span className="w-3/4">
                  {selectedAccount ? selectedAccountName : 'The selected trades person'}{' '}
                  Receives
                </span>
                <span className="w-1/4 text-right font-black">£{merchantTotal / 100}</span>
              </div>
              <div className="flex p-4 border-b-2">
                <span className="w-3/4">Kingfisher Commission Fee</span>
                <span className="w-1/4 text-right font-black">
                  £{(marketplaceFee / 100).toFixed(2)}
                </span>
              </div>

              <div className="flex flex-col p-4  space-y-4">
                <span className="w-full font-bold">Select the trades person</span>
                <div className="w-full">
                  <select
                    onChange={e => setDriver(e.target)}
                    id="testState"
                    name="testState"
                    className="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                  >
                    <option>Select Company</option>
                    {sellers.map(seller => {
                      return (
                        <option key={seller.accountCode} value={seller.accountCode}>
                          {seller.accountHolder}
                        </option>
                      );
                    })}
                  </select>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}
