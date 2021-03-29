module.exports = {
  publicPath: process.env.NODE_ENV === "production" ? "/~alauni/cs450/" : "/",
  devServer: {
    proxy: "http://localhost:8888",
  },
};
