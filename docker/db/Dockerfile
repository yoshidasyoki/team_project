FROM mysql:8.4

# MySQLで日本語を使用するための設定
RUN microdnf install -y glibc-langpack-ja
ENV LANG=ja_JP.utf8
