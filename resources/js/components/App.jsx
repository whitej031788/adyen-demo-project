import { h } from 'preact';

import Route from "./Route.jsx";
import AfpOnboarding from "./AfpOnboarding.jsx";
import AfpPayment from "./AfpPayment.jsx";

export default () => {
  return (
    <div>
      <Route path="/afp-onboarding">
        <AfpOnboarding />
      </Route>
      <Route path="/afp-payment">
        <AfpPayment />
      </Route>
      <Route path="/all-preact-components">
        <AfpOnboarding />
      </Route>
    </div>
  )
}
