export const plugin = store => {
    // called when the store is initialized
    store.subscribe((mutation, state) => {
        // console.log(mutation,state);
        // called after every mutation.
        // The mutation comes in the format of `{ type, payload }`.
    })
}