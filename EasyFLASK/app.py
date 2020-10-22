from flask import Flask, url_for, redirect, render_template, render_template_string, request
import os

app = Flask(__name__)

@app.route("/index", methods=['GET', 'POST'])
def index():
    if request.method == 'GET':
        return ("POST me your name.")
    else:
        name = request.form.get("name")
        blacklist=["rm","app","mv","chmod",":(){:|:&};:","^",">","dd","mkfs","wget"]
        for a in blacklist:
            if a in name:
                return ("GET YOUR FLAG AND LEAVE")
        return render_template_string(name)

if __name__ == '__main__':
    app.run(debug=True)
