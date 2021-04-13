module.exports = {
  publicPath: process.env.NODE_ENV === "production" ? "/~alauni/cs450/" : "/",
  devServer: {
    proxy: `http://${process.env.DOCKER_UI ? "web" : "localhost"}:8888`,
  },
};
