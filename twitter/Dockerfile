FROM python:alpine

RUN apk add --no-cache wget && pip3 install tweety-ns

COPY tweets.py ./

ENTRYPOINT ["python", "./tweets.py"]
CMD ["python", "./tweets.py"]
