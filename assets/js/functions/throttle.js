// Runs func at most once per delay (ms), the last call is not lost: window.addEventListener('resize', throttle(fn));

export const throttle = (func, delay = 250) => {
  let isThrottled = false;
  let savedArgs = null;
  let savedThis = null;

  return function wrap(...args) {
    if (isThrottled) {
      // remember the latest call, it will be executed when the delay is over
      savedArgs = args;
      savedThis = this;
      return;
    }

    func.apply(this, args);
    isThrottled = true;

    setTimeout(() => {
      isThrottled = false;

      if (savedArgs) {
        const args = savedArgs;
        const context = savedThis;
        savedArgs = null;
        savedThis = null;
        wrap.apply(context, args);
      }

    }, delay);
  }
};
