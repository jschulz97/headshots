import time
import os
import sys
import threading
import yaml
import csv

from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.chrome.service import Service
from webdriver_manager.chrome import ChromeDriverManager


csv_roster = 'roster.csv'


def scrape_roster(attr_xpaths):
    # print(attr_xpaths['Number'])
    # print(attr_xpaths['Next Player Number'])

    eles_1 = attr_xpaths['Number'].split('/')
    eles_2 = attr_xpaths['Next Player Number'].split('/')
    base_xpath = ''
    for e1, e2 in zip(eles_1, eles_2):
        if(e1 != e2):
            tag = e1.split('[')[0]
            index = int(e1.split('[')[1].strip(']'))
            base_xpath += '/' + tag + '['
            break
        else: 
            base_xpath += '/' + e1
    
    base_xpath = base_xpath[1:]
    
    roster = []

    while(True):
        # suffix path
        def suffix_xpath(attr): return ']' + attr_xpaths[attr][len(base_xpath)+2:]
        
        try:
        
            # Number
            obj = driver.find_element(By.XPATH, base_xpath+str(index)+suffix_xpath('Number'))
            number = obj.text

            # Name
            obj = driver.find_element(By.XPATH, base_xpath+str(index)+suffix_xpath('Name'))
            f_name = obj.text.split(' ')[0]
            l_name = obj.text[len(obj.text.split(' ')[0])+1:].strip()

            # Year
            obj = driver.find_element(By.XPATH, base_xpath+str(index)+suffix_xpath('Year'))
            year = obj.text

            # Position
            obj = driver.find_element(By.XPATH, base_xpath+str(index)+suffix_xpath('Position'))
            pos = obj.text

            # Height
            obj = driver.find_element(By.XPATH, base_xpath+str(index)+suffix_xpath('Height'))
            height = "'"+obj.text

            roster.append([number,f_name,l_name,height,pos,year])

            index += 1
        
        except:
            break

    return roster




def scrape_coaches(attr_xpaths):
    # print(attr_xpaths['Coach Name'])
    # print(attr_xpaths['Next Coach Name'])

    eles_1 = attr_xpaths['Coach Name'].split('/')
    eles_2 = attr_xpaths['Next Coach Name'].split('/')
    base_xpath = ''
    for e1, e2 in zip(eles_1, eles_2):
        if(e1 != e2):
            tag = e1.split('[')[0]
            index = int(e1.split('[')[1].strip(']'))
            base_xpath += '/' + tag + '['
            break
        else: 
            base_xpath += '/' + e1
    
    base_xpath = base_xpath[1:]
    
    roster = []

    while(True):
        # suffix path
        def suffix_xpath(attr): return ']' + attr_xpaths[attr][len(base_xpath)+2:]
        
        try:
        
            # Number
            obj = driver.find_element(By.XPATH, base_xpath+str(index)+suffix_xpath('Coach Title'))
            title = obj.text

            # Name
            obj = driver.find_element(By.XPATH, base_xpath+str(index)+suffix_xpath('Coach Name'))
            l_name = obj.text.split(' ')[-1]
            f_name = obj.text[:-len(obj.text.split(' ')[-1])].strip()

            roster.append(['',f_name,l_name,'',title,''])

            index += 1
        
        except:
            break

    return roster



def scrape_headshots(attr_xpaths):
    # print(attr_xpaths['Card Headshot'])
    # print(attr_xpaths['Next Player Card Headshot'])

    eles_1 = attr_xpaths['Card Headshot'].split('/')
    eles_2 = attr_xpaths['Next Player Card Headshot'].split('/')
    base_xpath = ''
    for e1, e2 in zip(eles_1, eles_2):
        if(e1 != e2):
            tag = e1.split('[')[0]
            index = int(e1.split('[')[1].strip(']'))
            base_xpath += '/' + tag + '['
            break
        else: 
            base_xpath += '/' + e1
    
    base_xpath = base_xpath[1:]
    
    headshots = []

    while(True):
        # suffix path
        def suffix_xpath(attr): return ']' + attr_xpaths[attr][len(base_xpath)+2:]
        
        try:
        
            # Headshot
            obj = driver.find_element(By.XPATH, base_xpath+str(index)+suffix_xpath('Card Headshot'))
            hs = obj.get_attribute('outerHTML')
            j = hs.find('url')
            sub = hs[j+5:].split('?')[0]

            headshots.append(sub+'?width=100&height=110&mode=crop&anchor=topcenter')
            
            index += 1
        
        except:
            break
    
    return headshots



def scrape_coach_headshots(attr_xpaths):
    # print(attr_xpaths['Coach Card Headshot'])
    # print(attr_xpaths['Next Coach Card Headshot'])

    eles_1 = attr_xpaths['Coach Card Headshot'].split('/')
    eles_2 = attr_xpaths['Next Coach Card Headshot'].split('/')
    base_xpath = ''
    for e1, e2 in zip(eles_1, eles_2):
        if(e1 != e2):
            tag = e1.split('[')[0]
            index = int(e1.split('[')[1].strip(']'))
            base_xpath += '/' + tag + '['
            break
        else: 
            base_xpath += '/' + e1
    
    base_xpath = base_xpath[1:]
    
    headshots = []

    while(True):
        # suffix path
        def suffix_xpath(attr): return ']' + attr_xpaths[attr][len(base_xpath)+2:]
        
        try:
        
            # Headshot
            obj = driver.find_element(By.XPATH, base_xpath+str(index)+suffix_xpath('Coach Card Headshot'))
            hs = obj.get_attribute('outerHTML')
            j = hs.find('url')
            sub = hs[j+5:].split('?')[0]

            headshots.append(sub+'?width=100&height=110&mode=crop&anchor=topcenter')
            
            index += 1
        
        except:
            break
    
    return headshots



def confirm_selections(attr_list, file_path):
    attr_xpaths = dict()

    for i in range(len(attr_list)):
        try: 
            res = input('Press "Enter" to confirm last click as "'+attr_list[i]+'" (or press "n" to undo)')

            if(res.lower() != 'n'):

                # print('Committing the following XPath...')
                # print(last_result)
                # print('##################################\n')

                attr_xpaths[attr_list[i]] = last_result

            else:
                i -= 1

            if(attr_list[i] == 'Next Player Number'):
                attrs = scrape_roster(attr_xpaths)
            
            if(attr_list[i] == 'Next Player Card Headshot'):
                headshots = scrape_headshots(attr_xpaths)

            if(attr_list[i] == 'Next Coach Name'):
                coaches = scrape_coaches(attr_xpaths)

            if(attr_list[i] == 'Next Coach Card Headshot'):
                coach_hs = scrape_coach_headshots(attr_xpaths)

            time.sleep(.5)
        
        except Exception as e:
            print("bummer, failed that last one")
            print(e)
    

    with open(csv_roster, 'w') as fp:
        writer = csv.writer(fp)
        try:
            for attr, hs in zip(attrs, headshots):
                writer.writerow([*attr, hs])
        except Exception as e:
            print('failed writing roster')
            print(e)

        try:
            for attr, hs in zip(coaches, coach_hs):
                writer.writerow([*attr, hs])
        except Exception as e:
            print('failed writing coaches')
            print(e)

        
        print('Attributes Exported. Quit now.')


    # with open(file_path, 'w') as fp:
    #     yaml.dump(attr_xpaths, fp, Dumper=yaml.CDumper)
    #     




if __name__ == '__main__':
    driver = webdriver.Chrome(service=Service(ChromeDriverManager().install()))
    # driver.get('https://davidsonwildcats.com/sports/womens-basketball/roster?path=wbball')
    # driver.get('https://goduquesne.com/sports/womens-basketball/roster?path=wbball')
    url = input("Enter url: ")
    driver.get(url)

   
    with open('js_utils.js') as fp:

        script = fp.read()


    last_result = ''
    attr_list = [
        'Number',
        'Name',
        'Year',
        'Height',
        'Position',
        'Next Player Number',
        'Coach Name',
        'Coach Title',
        'Next Coach Name',
        'Card Headshot',
        'Next Player Card Headshot',
        'Coach Card Headshot',
        'Next Coach Card Headshot'
    ]

    driver.execute_script(script)

    
    confirm_selection_thread = threading.Thread(target=confirm_selections, args=(attr_list,'roster_xpath.yml',))
    confirm_selection_thread.start()

    time.sleep(1)

    while(True):

        try:
            result = driver.execute_script('return getLastResult();')
        except Exception as e:
            driver.execute_script(script)

        if(result != last_result):
            # print(result)
            last_result = result


        time.sleep(.1)
