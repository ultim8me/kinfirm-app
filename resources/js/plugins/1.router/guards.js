import { useUserStore } from "@/stores/user";

export const setupGuards = router => {

  router.beforeEach(async (to, from) => {

    if (to?.meta?.public) {
      return
    }

    console.log(to);

    const store = useUserStore()

    const isLoggedIn = await store.isAuthenticated()

    if (to?.meta?.unauthenticatedOnly) {
      console.log("im here unauthenticatedOnly " + isLoggedIn);
      if (isLoggedIn)
        return { name: 'root' }
      else
        return
    }

    if (!isLoggedIn) {
      console.log("!isLoggedIn");
      return {
        name: 'auth-login',
        query: {
          ...to.query,
          to: to.fullPath !== '/' ? to.path : undefined,
        },
      }
    }
  })
}
