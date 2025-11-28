import glob
import os

# files = glob.glob("C:\\xampp\\htdocs\\php\\Code-Crusher-project\\customer\\gadget-images\\*")
# for file in files:
#     print(file)

img_path = "C:\\xampp\\htdocs\\php\\Code-Crusher-project\\customer\\gadget-images\\"
# print(img_path)

file_list=[]

path_list=[img_path + "gadgets-7_" + str(i) + ".jpg" for i in range(1, 9)]
print(path_list)
i = 0
for path in path_list:
    i += 1
    dirname, file_and_ext = os.path.split(path)
    file, ext = os.path.splitext(file_and_ext)
    new_file_name = "gadgets-" + "7" +"_" + str(i) + ext
    os.rename(path, os.path.join(dirname, new_file_name))